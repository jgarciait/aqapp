<div>
    <aside class="socialSidebar expanded" id="sidebar2">
        <div class="toggle-button-container2">
            <button type="button" class="icon-sm" id="toggleButton2"><i class="fa fa-circle-dot"></i></button>
        </div>
        <nav>
            <p>DCS Team</p>
            <div class="scroll-container" style="height: 200px; overflow-y: auto;">
                <ul id="start-time"></ul>
            </div>

            <script>
                function updateStartTime() {
                    console.log('Sending AJAX request...');
                    $.ajax({
                        url: 'core/assets/ajax/get_start_time.php',
                        method: 'GET',
                        dataType: 'json',
                        success: function (data) {
                            console.log('Received data:', data);
                            var $startTime = $('#start-time');
                            $startTime.empty(); // Clear the list before adding new data

                            if (data.length > 0) {
                                data.forEach(function (wtc) {
                                    var $li = $('<li class="has-subnav">');
                                    $li.append('<i class="fa fa-user fa-1x"></i>');
                                    $li.append('<span class="nav-text">' + wtc.first_name + ' ' + wtc.last_name + ' start at:</span>');

                                    var timestamp = new Date(wtc.start_time);
                                    var formattedDate = timestamp.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
                                    var formattedTime = timestamp.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

                                    $li.append(formattedDate + ' ' + formattedTime);
                                    $li.append('<hr>');

                                    $startTime.append($li);
                                });
                            } else {
                                $startTime.append('<li>No data available.</li>');
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error('Failed to fetch data. Status:', status, 'Error:', error);
                        }
                    });
                }

                // Call the function initially to load the data
                updateStartTime();

                // Set an interval to refresh the data every X seconds (e.g., 30 seconds)
                setInterval(updateStartTime, 5000); // 30 seconds
            </script>

        </nav>
    </aside>
</div>
