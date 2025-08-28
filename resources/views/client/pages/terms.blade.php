@extends('client.layouts.app')

@section('title', 'Điều khoản và Điều kiện - Techvicom')

@section('content')
    <main class="min-h-screen">
        <!-- Header Section -->
        <section class="bg-gray-50 py-12">
            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        Điều khoản và Điều kiện
                    </h1>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                        Quy định sử dụng dịch vụ và mua bán hàng hóa tại Techvicom
                    </p>
                </div>
            </div>
        </section>

        <!-- General Terms -->
        <section id="general" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">1. Điều khoản chung</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <p class="text-gray-700 mb-4">
                            Các điều khoản và điều kiện này áp dụng cho tất cả các giao dịch mua bán hàng hóa và dịch vụ 
                            thông qua website <strong>techvicom.vn</strong> và các kênh bán hàng chính thức của Techvicom.
                        </p>
                        <p class="text-gray-700">
                            Bằng việc sử dụng dịch vụ của chúng tôi, quý khách hàng đồng ý tuân thủ các điều khoản và 
                            điều kiện được quy định dưới đây.
                        </p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Quyền của khách hàng</h4>
                            <ul class="text-gray-600 space-y-2 text-sm">
                                <li>• Được cung cấp thông tin chính xác về sản phẩm</li>
                                <li>• Được bảo mật thông tin cá nhân</li>
                                <li>• Được hỗ trợ kỹ thuật và chăm sóc khách hàng</li>
                                <li>• Được đổi trả sản phẩm theo chính sách</li>
                            </ul>
                        </div>

                        <div class="border-l-4 border-orange-500 pl-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-3">Nghĩa vụ của khách hàng</h4>
                            <ul class="text-gray-600 space-y-2 text-sm">
                                <li>• Cung cấp thông tin chính xác khi đặt hàng</li>
                                <li>• Thanh toán đầy đủ theo phương thức đã chọn</li>
                                <li>• Tuân thủ các điều khoản sử dụng</li>
                                <li>• Thông báo kịp thời khi có thay đổi</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Order Terms -->
        <section id="orders" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">2. Điều khoản đặt hàng</h2>
                    
                    <div class="bg-white rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Quy trình đặt hàng</h3>
                        <ol class="text-gray-700 space-y-3">
                            <li><strong>1.</strong> Chọn sản phẩm và thêm vào giỏ hàng</li>
                            <li><strong>2.</strong> Điền thông tin giao hàng chính xác</li>
                            <li><strong>3.</strong> Chọn phương thức thanh toán (COD, chuyển khoản, VNPay)</li>
                            <li><strong>4.</strong> Xác nhận và hoàn tất đơn hàng</li>
                        </ol>
                    </div>

                    <div class="bg-white rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Xác nhận đơn hàng</h3>
                        <div class="text-gray-700 space-y-2">
                            <p>• Nhân viên sẽ gọi điện xác nhận trong vòng <strong>30 phút - 2 giờ</strong></p>
                            <p>• Email xác nhận được gửi ngay sau khi đặt hàng</p>
                            <p>• Vui lòng giữ máy và kiểm tra email (bao gồm spam)</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Payment Terms -->
        <section id="payment" class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">3. Điều khoản thanh toán</h2>
                    
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Phương thức thanh toán</h3>
                        <div class="space-y-3 text-gray-700">
                            <div>
                                <strong>Thanh toán khi nhận hàng (COD):</strong> Thanh toán bằng tiền mặt khi nhận hàng. Áp dụng cho tất cả đơn hàng trong nội thành Hà Nội.
                            </div>
                            <div>
                                <strong>Chuyển khoản ngân hàng:</strong> Chuyển khoản trước khi giao hàng. Đơn hàng được xử lý sau khi xác nhận thanh toán.
                            </div>
                            <div>
                                <strong>Thanh toán online VNPay:</strong> Thanh toán trực tuyến qua thẻ ATM, thẻ tín dụng, ví điện tử thông qua cổng VNPay.
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Chính sách thanh toán trước</h3>
                        <div class="text-gray-700 space-y-2 mb-4">
                            <p>• Đơn hàng chỉ được xử lý sau khi xác nhận thanh toán thành công</p>
                            <p>• Thời gian xác nhận thanh toán: 30 phút - 2 giờ làm việc</p>
                            <p>• Khách hàng vui lòng gửi ảnh chụp biên lai chuyển khoản qua email hoặc hotline</p>
                            <p>• Số tiền chuyển khoản phải khớp chính xác với tổng tiền đơn hàng</p>
                        </div>
                        <div class="border-t pt-4">
                            <h4 class="font-semibold text-gray-800 mb-2">Thông tin chuyển khoản</h4>
                            <p class="text-gray-600 text-sm mb-3">
                                Thông tin tài khoản ngân hàng sẽ được cung cấp sau khi xác nhận đơn hàng. 
                                Nhân viên sẽ liên hệ trực tiếp với khách hàng để hướng dẫn thanh toán.
                            </p>
                            <div class="text-sm space-y-1">
                                <p><strong>Hotline:</strong> 1800.6601 để được hỗ trợ thông tin thanh toán</p>
                                <p><strong>Email:</strong> techvicom@gmail.com để được hỗ trợ thanh toán</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cancellation Terms -->
        <section id="cancellation" class="py-16 bg-gray-50">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">4. Chính sách hủy đơn hàng</h2>
                    
                    <div class="bg-white rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Hủy đơn hàng đã thanh toán trước</h3>
                        <p class="text-gray-700 mb-4">
                            Đối với khách hàng đã thanh toán trước nhưng muốn hủy đơn hàng, Techvicom sẽ xử lý theo quy trình sau:
                        </p>
                            
                        <h4 class="font-semibold text-gray-800 mb-3">Thời gian cho phép hủy đơn hàng</h4>
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h5 class="font-semibold text-gray-800 mb-2">Được phép hủy</h5>
                                <ul class="text-gray-600 space-y-1 text-sm">
                                    <li>• Trong vòng 12 giờ sau khi đặt hàng</li>
                                    <li>• Trước khi đơn hàng chuyển sang trạng thái "Đang chuẩn bị"</li>
                                    <li>• Khi sản phẩm chưa được đóng gói</li>
                                </ul>
                            </div>
                            <div class="border-l-4 border-red-500 pl-4">
                                <h5 class="font-semibold text-gray-800 mb-2">Không được phép hủy</h5>
                                <ul class="text-gray-600 space-y-1 text-sm">
                                    <li>• Sau khi đơn hàng đã được đóng gói</li>
                                    <li>• Khi đơn hàng đã giao cho đơn vị vận chuyển</li>
                                    <li>• Đơn hàng đang trên đường giao</li>
                                </ul>
                            </div>
                        </div>

                        <h4 class="font-semibold text-gray-800 mb-3">Quy trình hoàn tiền</h4>
                        <ol class="text-gray-700 space-y-2 text-sm mb-4">
                            <li><strong>1. Liên hệ hủy đơn hàng:</strong> Khách hàng gọi hotline 1800.6601 hoặc gửi email đến techvicom@gmail.com để thông báo hủy đơn hàng.</li>
                            <li><strong>2. Xác nhận thông tin:</strong> Nhân viên xác nhận thông tin đơn hàng, lý do hủy và thông tin tài khoản nhận hoàn tiền.</li>
                            <li><strong>3. Xử lý hoàn tiền:</strong> Techvicom sẽ hoàn tiền trong vòng 3-5 ngày làm việc qua chuyển khoản ngân hàng hoặc hoàn về phương thức thanh toán ban đầu.</li>
                            <li><strong>4. Thông báo hoàn tất:</strong> Khách hàng nhận được email xác nhận hoàn tiền và có thể theo dõi trạng thái qua mã đơn hàng.</li>
                        </ol>

                        <div class="border-t pt-4">
                            <h5 class="font-semibold text-gray-800 mb-2">Lưu ý quan trọng</h5>
                            <ul class="text-gray-600 space-y-1 text-sm">
                                <li>• Phí giao dịch ngân hàng (nếu có) sẽ được khách hàng chịu</li>
                                <li>• Đối với thanh toán qua VNPay, thời gian hoàn tiền có thể từ 5-7 ngày làm việc</li>
                                <li>• Khách hàng cần cung cấp đầy đủ thông tin tài khoản để nhận hoàn tiền</li>
                                <li>• Mọi thắc mắc về hoàn tiền, vui lòng liên hệ bộ phận chăm sóc khách hàng</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Hủy đơn hàng thanh toán khi nhận hàng (COD)</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="border-l-4 border-green-500 pl-4">
                                <h4 class="font-semibold text-gray-800 mb-2">Dễ dàng hủy đơn hàng</h4>
                                <p class="text-gray-600 mb-3 text-sm">
                                    Đơn hàng COD có thể được hủy dễ dàng hơn vì chưa có giao dịch thanh toán phát sinh.
                                </p>
                                <ul class="text-gray-600 space-y-1 text-sm">
                                    <li>• Có thể hủy bất cứ lúc nào trước khi giao hàng</li>
                                    <li>• Không mất phí hủy đơn hàng</li>
                                    <li>• Chỉ cần gọi điện hoặc gửi email thông báo</li>
                                </ul>
                            </div>
                            <div class="border-l-4 border-orange-500 pl-4">
                                <h4 class="font-semibold text-gray-800 mb-2">Lưu ý khi hủy COD</h4>
                                <p class="text-gray-600 mb-3 text-sm">
                                    Việc hủy đơn hàng COD liên tục có thể ảnh hưởng đến uy tín của khách hàng.
                                </p>
                                <ul class="text-gray-600 space-y-1 text-sm">
                                    <li>• Hủy quá 3 lần/tháng sẽ bị hạn chế đặt hàng</li>
                                    <li>• Cần lý do hợp lý khi hủy đơn hàng</li>
                                    <li>• Ưu tiên thông báo sớm để tiết kiệm chi phí vận chuyển</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section class="py-12 bg-gray-800 text-white">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-2xl font-bold mb-4">Cần hỗ trợ về điều khoản?</h2>
                    <p class="text-gray-300 mb-6">
                        Đội ngũ chăm sóc khách hàng sẵn sàng giải đáp mọi thắc mắc về điều khoản và điều kiện
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-6">
                        <a href="tel:18006601" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                            Hotline: 1800.6601
                        </a>
                        <a href="mailto:techvicom@gmail.com" class="border border-gray-500 text-gray-300 hover:bg-gray-700 px-6 py-3 rounded-lg font-semibold transition-colors">
                            techvicom@gmail.com
                        </a>
                    </div>
                    <div class="text-sm text-gray-400 space-y-1">
                        <p>Điều khoản có hiệu lực từ ngày: {{ date('d/m/Y') }}</p>
                        <p>Cập nhật lần cuối: {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
