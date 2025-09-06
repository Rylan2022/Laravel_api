<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $product['name'] }}</title>
    @vite('resources/css/app.css') {{-- Tailwind --}}
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="max-w-sm bg-white shadow-lg rounded-2xl p-6">
        <img src="https://m.media-amazon.com/images/I/61x3xPK2UUL.jpg" 
        
             alt="Product Image" 
             class="rounded-xl mb-4 w-full h-48 object-cover">

        <h2 class="text-xl font-bold text-gray-800">{{ $product['name'] }}</h2>
        <p class="text-gray-600 mt-2">High-quality sound with deep bass and noise cancellation.</p>

        <div class="mt-4 flex items-center justify-between">
            <span class="text-lg font-semibold text-gray-900">
                ${{ number_format($product['price'] / 100, 2) }}
            </span>

            <form action="{{ route('session') }}" method="POST">
                @csrf
                <input type="hidden" name="product_name" value="{{ $product['name'] }}">
                <input type="hidden" name="price" value="{{ $product['price'] }}">

                <button type="submit" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition">
                    Buy Now
                </button>
            </form>
        </div>
    </div>

</body>
</html>
