<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                <li class="nav-item dropdown no-arrow d-sm-none">
                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-search fa-fw"></i>
                    </a>
                    <!-- Dropdown - Messages -->
                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                        aria-labelledby="searchDropdown">
                        <form class="form-inline mr-auto w-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                    placeholder="Search for..." aria-label="Search"
                                    aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>

               

                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span id="notificationBadge" class="badge badge-danger badge-counter" style="display: none;">0</span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header">Alerts Center</h6>
                        <div id="notificationList">
                            <p class="text-center small text-gray-500">Loading...</p>
                        </div>
                        <a href="javascript:void(0)" class="dropdown-item text-center small text-gray-500" id="showAllAlerts">Show All Alerts</a>
                    </div>
                </li>
                
                
                

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                        <img src="{{ Auth::user()->images ? asset('storage/' . Auth::user()->images) : 'https://via.placeholder.com/40' }}" class="rounded-circle me-2" alt="Profile" width="40">

                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href=" {{ url('profileseller') }}">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            Profile
                        </a>
                       
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for All Notifications -->
<div class="modal fade" id="allNotificationsModal" tabindex="-1" aria-labelledby="allNotificationsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allNotificationsModalLabel">All Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-primary">Alerts</h6>
                <div id="alertsContainer" class="mb-4">
                    <p class="text-muted small">No alerts available.</p>
                </div>
                <h6 class="text-success">Messages</h6>
                <div id="messagesContainer">
                    <p class="text-muted small">No messages available.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function fetchNotifications() {
        $.ajax({
            url: "{{ route('notifications.get') }}",
            method: "GET",
            success: function(response) {
                let notifications = response.notifications;
                let unreadCount = response.unreadCount;

                // Update Badge
                if (unreadCount > 0) {
                    $('#notificationBadge').text(unreadCount).show();
                } else {
                    $('#notificationBadge').hide();
                }

                // Update Notification List
                let notificationList = '';
                if (notifications.length > 0) {
                    notifications.forEach(notification => {
                        notificationList += `
                            <a class="dropdown-item d-flex align-items-center" href="#" onclick="markAsRead(${notification.id})">
                                <div class="mr-3">
                                    <div class="icon-circle bg-${notification.type === 'alert' ? 'warning' : 'primary'}">
                                        <i class="fas ${notification.type === 'alert' ? 'fa-exclamation-triangle' : 'fa-file-alt'} text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="small text-gray-500">${new Date(notification.created_at).toLocaleDateString()}</div>
                                    <span class="${notification.is_read ? 'text-muted' : 'font-weight-bold'}">
                                        ${notification.message}
                                    </span>
                                </div>
                            </a>
                        `;
                    });
                } else {
                    notificationList = '<p class="text-center small text-gray-500">No new notifications</p>';
                }
                $('#notificationList').html(notificationList);
            }
        });
    }

    function markAsRead(notificationId) {
        $.ajax({
            url: "{{ route('notifications.mark-read') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                notification_id: notificationId
            },
            success: function() {
                fetchNotifications(); // Refresh notifications
            }
        });
    }

    // Fetch notifications on page load
    $(document).ready(function() {
        fetchNotifications();

        // Optionally: Poll for new notifications every X seconds
        setInterval(fetchNotifications, 30000); // 30 seconds
    });
</script>
<script>
    $(document).ready(function () {
        // Handle "Show All Alerts" button click
        $('#showAllAlerts').on('click', function () {
            // Open modal
            $('#allNotificationsModal').modal('show');

            // Clear existing content
            $('#alertsContainer').html('<p class="text-muted small">Loading...</p>');
            $('#messagesContainer').html('<p class="text-muted small">Loading...</p>');

            // Fetch notifications via AJAX
            $.ajax({
                url: '{{ route("notifications.all") }}', // Route to fetch notifications
                type: 'GET',
                success: function (response) {
                    // Populate alerts
                    if (response.alerts.length > 0) {
                        let alertsHtml = '';
                        response.alerts.forEach(alert => {
                            alertsHtml += `
                                <div class="alert alert-warning" role="alert">
                                    ${alert.message}
                                    <span class="small text-muted d-block">${alert.created_at}</span>
                                </div>`;
                        });
                        $('#alertsContainer').html(alertsHtml);
                    } else {
                        $('#alertsContainer').html('<p class="text-muted small">No alerts available.</p>');
                    }

                    // Populate messages
                    if (response.messages.length > 0) {
                        let messagesHtml = '';
                        response.messages.forEach(message => {
                            messagesHtml += `
                                <div class="alert alert-info" role="alert">
                                    ${message.message}
                                    <span class="small text-muted d-block">${message.created_at}</span>
                                </div>`;
                        });
                        $('#messagesContainer').html(messagesHtml);
                    } else {
                        $('#messagesContainer').html('<p class="text-muted small">No messages available.</p>');
                    }
                },
                error: function () {
                    $('#alertsContainer').html('<p class="text-danger small">Failed to load alerts.</p>');
                    $('#messagesContainer').html('<p class="text-danger small">Failed to load messages.</p>');
                }
            });
        });
    });
</script>
