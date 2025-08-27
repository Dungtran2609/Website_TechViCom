<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use App\Services\ChatbotService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use App\Models\Order;
use App\Models\News;
use App\Models\Promotion;
use App\Models\User;

class ChatbotController extends Controller
{
    protected $chatbotService;

    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->input('message');
        // Sử dụng AI để phân tích intent và entity
        $analysis = $this->chatbotService->analyzeMessage($userMessage);
        $intent = $analysis['intent'] ?? 'general';
        $entities = $analysis['entities'] ?? [];
        $user = Auth::user();
        $context = $this->chatbotService->getContextData($intent, $entities, $user);

        $systemPrompt = "Bạn là trợ lý ảo chuyên nghiệp của website TechViCom. Luôn trả lời ngắn gọn, thân thiện, chính xác, có thể mở rộng giải thích nếu cần. Nếu không đủ dữ liệu, hãy đề xuất khách liên hệ hotline hoặc để lại thông tin.";

        $fullPrompt = $systemPrompt;
        if ($context) {
            $fullPrompt .= "\nDữ liệu thực tế liên quan: " . $context;
        }
        $fullPrompt .= "\nCâu hỏi của khách: " . $userMessage;

        $apiKey = config('services.gemini.api_key');
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-goog-api-key' => $apiKey,
        ])->post($url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $fullPrompt]
                    ]
                ]
            ]
        ]);

        // Xử lý response và trả về kết quả cho frontend (tùy ý)
        return response()->json([
            'reply' => $response['candidates'][0]['content']['parts'][0]['text'] ?? 'Xin lỗi, tôi chưa có dữ liệu phù hợp.'
        ]);
    }
}
