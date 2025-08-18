<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatHistory;
use Google\Cloud\Dialogflow\V2\SessionsClient;
use Google\Cloud\Dialogflow\V2\QueryInput;
use Google\Cloud\Dialogflow\V2\TextInput;
use Exception;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Nhận tin nhắn từ người dùng, gửi đến Dialogflow và trả về phản hồi.
     */
    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $userMessage = $request->input('message');

        // --- BẮT ĐẦU THAY ĐỔI: Quản lý Session ID ổn định ---
        if (!$request->session()->has('chatbot_session_id')) {
            // Nếu chưa có, tạo một ID duy nhất và lưu vào session
            $request->session()->put('chatbot_session_id', uniqid('chatbot_', true));
        }
        // Luôn lấy ID đã được lưu trữ ổn định
        $sessionId = $request->session()->get('chatbot_session_id');
        // --- KẾT THÚC THAY ĐỔI ---

        try {
            // Gọi hàm detectIntent để lấy câu trả lời
            $botResponse = $this->detectIntent($userMessage, $sessionId);

            // Lưu lịch sử chat vào database
            ChatHistory::create([
                'session_id' => $sessionId,
                'user_message' => $userMessage,
                'bot_response' => $botResponse,
            ]);

            // Trả về câu trả lời thành công
            return response()->json(['reply' => $botResponse]);
        } catch (Exception $e) {
            // Ghi lại lỗi chi tiết vào file log
            Log::error('Dialogflow Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine());

            // --- THAY ĐỔI QUAN TRỌNG: TRẢ VỀ LỖI CHI TIẾT ---
            return response()->json(['error' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Hàm private để kết nối và lấy câu trả lời từ Dialogflow API.
     */
    private function detectIntent($text, $sessionId)
    {
        // Lấy thông tin cấu hình từ file .env
        $projectId = env('DIALOGFLOW_PROJECT_ID');
        $credentialsFilename = env('GOOGLE_APPLICATION_CREDENTIALS_FILENAME');
        $credentialsPath = storage_path('app/' . $credentialsFilename);

        // Khởi tạo Dialogflow SessionsClient
        $sessionsClient = new SessionsClient([
            'credentials' => json_decode(file_get_contents($credentialsPath), true)
        ]);

        // Tạo tên session
        $sessionName = $sessionsClient->sessionName($projectId, $sessionId);

        // Tạo input text
        $textInput = new TextInput();
        $textInput->setText($text);
        // THỬ NGHIỆM VỚI 'en' TRƯỚC
        $textInput->setLanguageCode('en');

        // Tạo query input
        $queryInput = new QueryInput();
        $queryInput->setText($textInput);

        // Gửi yêu cầu đến Dialogflow
        $response = $sessionsClient->detectIntent($sessionName, $queryInput);
        $queryResult = $response->getQueryResult();

        // Đóng kết nối
        $sessionsClient->close();

        // Trả về câu trả lời của bot
        return $queryResult->getFulfillmentText();
    }
}
