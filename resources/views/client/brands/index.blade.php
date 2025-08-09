@extends('client.layouts.app')

@section('title', 'Danh sách thương hiệu')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8 text-center">Danh sách thương hiệu</h1>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
        @forelse ($brands as $brand)
            <div class="text-center group">
                <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition group-hover:scale-105 relative">
                    @if ($brand->image)
                        <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg">
                    @else
                        <img src="{{ asset('client_css/images/brand-default.jpg') }}" alt="No image" class="w-16 h-16 mx-auto mb-4 object-cover rounded-lg">
                    @endif
                    <h3 class="font-semibold mb-2">{{ $brand->name }}</h3>
                    <p class="text-sm text-gray-500 mb-2">{{ $brand->description }}</p>
                    <a href="{{ route('brands.show', $brand->slug) }}" class="block mt-2 text-[#ff6c2f] font-semibold hover:underline">Xem chi tiết thương hiệu</a>
                </div>
            </div>
        @empty
            <div class="col-span-6 text-center text-gray-500">Không có thương hiệu nào.</div>
        @endforelse
    </div>
</div>
@endsection
