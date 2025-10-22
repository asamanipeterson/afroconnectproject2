@extends('layouts.app')

<html lang="en">
<head>
    @include('layouts.head')
    <link rel="stylesheet" href="{{ asset('css/showItem.css') }}">
    <style>
        /* Modal styles forinant modal */
        .item-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6);
            justify-content: center;
            align-items: center;
        }
        .item-modal-content {
            background: none;
            margin: 0;
            padding: 20px;
            width: 100%;
            height: 100%;
            max-width: none;
            box-sizing: border-box;
            position: relative;
            overflow-y: auto;
        }
        body.dark .item-modal-content {
            background: none;
        }
        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 40px;
            color: #fff;
            background-color: #333;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
            z-index: 1001;
        }
        body.dark-mode .close-btn {
            color: #fff;
            background-color: #555;
        }
        .close-btn:hover {
            background-color: #ff4444;
            color: #fff;
        }
        .main-contents {
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
        }
        .search-bar {
            position: relative;
        }
        .search-button {
            position: absolute;
            right: 5px;
            top: 40%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
        }
        .search-button i {
            font-size: 20px;
            color: #333;
        }
        body.dark-mode .search-button i {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Main Content -->
        <div class="content-wrapper">
            @section('content')
            <nav class="navbar">
                <div class="containers">
                    <div class="logo">
                        <a class="navbar-brand" href="{{ route('marketshowroom') }}">
                            <h3>Marketplace</h3>
                        </a>
                    </div>

                    <!-- Search Bar -->
                    <div class="d-flex mx-auto search-bar">
                        <form action="{{ route('marketshowroom') }}" method="GET" id="search-form">
                            <input class="form-control" type="search" name="category" placeholder="Search by category" aria-label="Search" value="{{ request('category') }}">
                            <button type="submit" class="search-button">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Right Side Icons -->
                    {{-- <div class="d-flex align-items-center">

                        <a href="{{ url('/inbox') }}" class="me-4 inbox-icon">
                            <i class="bi bi-inbox-fill"></i>
                        </a>
                    </div> --}}
                </div>

                <div class="create-listing-container">
                    <a href="{{ route('marketplace.newlisting') }}" class="create-listing">+ Create new listing</a>
                </div>
            </nav>

            <div class="cards-container">
                <h4 class="section-title">Today's picks</h4>
                @if($items->isEmpty())
                    <p>No items found for this category.</p>
                @else
                    <div class="grid-container">
                        <!-- Loop through the items and create a card for each one -->
                        @foreach($items as $item)
                            <div class="card-item-link" onclick="openItemModal({{ $item->id }})">
                                <div class="card-item">
                                    @if($item->photos->count() > 0)
                                        <img src="{{ asset('storage/' . $item->photos->first()->path) }}" alt="{{ $item->title }}" class="card-image">
                                    @else
                                        <div class="placeholder-image">No Image</div>
                                    @endif
                                    <div class="card-details">
                                        <p class="card-price">GHS{{ number_format($item->price, 2) }}</p>
                                        <p class="card-title">{{ $item->title }}</p>
                                        <p class="card-location">{{ $item->location }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Item Details Modal -->
            <div id="item-modal" class="item-modal">
                <span class="close-btn" onclick="closeItemModal()"><i class="bi bi-x-circle-fill"></i></span>
                <div id="item-modal-content" class="item-modal-content">
                    <!-- Dynamic content loaded here via AJAX -->
                </div>
            </div>

            <script>
                function openItemModal(id) {
                    fetch('{{ route("marketplace.show", ":id") }}'.replace(':id', id), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        document.getElementById('item-modal-content').innerHTML = html;
                        // Re-initialize image navigation script
                        const scripts = document.getElementById('item-modal-content').querySelectorAll('script');
                        scripts.forEach(oldScript => {
                            const newScript = document.createElement('script');
                            newScript.text = oldScript.text;
                            document.head.appendChild(newScript).parentNode.removeChild(newScript);
                        });
                        // Explicitly call initializeImageNavigation if it exists
                        if (typeof window.initializeImageNavigation === 'function') {
                            window.initializeImageNavigation();
                        }
                        document.getElementById('item-modal').style.display = 'flex';
                    })
                    .catch(error => console.error('Error loading item details:', error));
                }

                function closeItemModal() {
                    document.getElementById('item-modal').style.display = 'none';
                    document.getElementById('item-modal-content').innerHTML = ''; // Clear content to free resources
                }

                // Close modal when clicking outside content
                window.onclick = function(event) {
                    const modal = document.getElementById('item-modal');
                    if (event.target === modal) {
                        closeItemModal();
                    }
                }
            </script>
            @endsection
        </div>
    </div>
</body>
</html>
