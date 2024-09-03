<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Auth</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .dropdown-menu {
            max-height: 400px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('dashboard') }}">Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="form-inline my-2 my-lg-0 ml-auto" method="GET" action="{{ route('user.search') }}">
                <input class="form-control mr-sm-2" type="search" name="query" placeholder="Search users" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
            <ul class="navbar-nav ml-auto">
                @auth
                    <!-- Profile Button -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('user.myprofile') }}">Profile</a>
                    </li>

                    <!-- Notifications Button -->
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="modal" data-target="#notificationsModal">
                            Notifications
                        </a>
                    </li>

                    <!-- Logout Button -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        @yield('content')
    </div>

    <!-- Notifications Modal -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" role="dialog" aria-labelledby="notificationsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationsModalLabel">Notifications</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="notification-list" class="list-group">
                        <li class="list-group-item">No new notifications.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#notificationsModal').on('shown.bs.modal', function () {
                $.ajax({
                    url: '{{ route('notifications.index') }}',
                    method: 'GET',
                    success: function(data) {
                        var $notificationList = $('#notification-list');
                        $notificationList.empty();

                        if (data.length > 0) {
                            data.forEach(function(notification) {
                                var message = notification.type === 'follow' ? 'started following you' : 'followed you back';
                                $notificationList.append(
                                    `<li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ url('user/profile') }}/${notification.actor_id}">
                                            ${notification.actor_name} ${message}
                                        </a>
                                        <button class="btn btn-sm btn-primary follow-toggle" data-actor-id="${notification.actor_id}" data-action="${notification.type === 'follow' ? 'follow' : 'unfollow'}">
                                            ${notification.type === 'follow' ? 'Follow' : 'Unfollow'}
                                        </button>
                                    </li>`
                                );
                            });
                        } else {
                            $notificationList.append('<li class="list-group-item">No new notifications.</li>');
                        }
                    },
                    error: function() {
                        var $notificationList = $('#notification-list');
                        $notificationList.empty();
                        $notificationList.append('<li class="list-group-item text-danger">Error loading notifications. Please try again later.</li>');
                    }
                });
            });

            // Handle follow/unfollow button clicks
            $(document).on('click', '.follow-toggle', function() {
                var $button = $(this);
                var actorId = $button.data('actor-id');
                var action = $button.data('action');
                var url = action === 'follow' ? '{{ route('notifications.follow') }}' : '{{ route('notifications.unfollow') }}';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        actor_id: actorId,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        var newAction = action === 'follow' ? 'unfollow' : 'follow';
                        var newText = action === 'follow' ? 'Unfollow' : 'Follow';
                        $button.data('action', newAction).text(newText);
                    },
                    error: function() {
                        alert('Error performing action. Please try again later.');
                    }
                });
            });
        });
    </script>
</body>
</html>
