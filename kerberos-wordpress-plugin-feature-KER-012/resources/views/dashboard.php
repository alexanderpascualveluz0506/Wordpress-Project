<h1>Dashboard</h1>
<?php

  global $wpdb;
    $results = $wpdb->get_results('SELECT id, datetime, notes, service_id, customer_id, provider_id, status FROM '.$wpdb->prefix.'kerberos');
    foreach ($results as $event) {
        $events[] = $event;        
    }
    $tempjson = json_encode($events);
    $tempjson = str_replace('"datetime"', 'date', $tempjson);
    $tempjson = str_replace('"notes"', 'title', $tempjson);
    $tempjson = str_replace('"status"', 'color', $tempjson);
    $tempjson = str_replace('"approved"', '"green"', $tempjson);
    $tempjson = str_replace('"pending"', '"yellow"', $tempjson);
    $tempjson = str_replace('"cancelled"', '"red"', $tempjson);
    $tempjson = str_replace('"rescheduled"', '"blue"', $tempjson);
    echo $tempjson;
    //Get info


    
  //ajax plugin url drag
  $plugin_url = plugin_dir_url(__FILE__);
  $drag = $plugin_url .'event-drag.php';
    if (isset($_POST['submit_booking'])) {
        $table_name = $wpdb->prefix. 'kerberos';

        $date = $_POST['date'];
        $time = $_POST['time'];
        $service = $_POST['service_id'];
        $customer_id = $_POST['customer_id'];
        $provider_id = $_POST['provider_id'];
        $status = $_POST['status'];
        $notes = $_POST['notes'];
        $datetime = $date . '  ' . $time . ':00';
        $wpdb->insert($table_name, [
          'datetime' => $datetime,
          'status' => $status,
          'notes' => $notes,
          'service_id' => $service,
          'customer_id' => $customer_id,
          'provider_id' => $provider_id,
        ]);
    }


    ?>
<!-- Calendar Div -->    
<div id='calendar'></div>

<!-- Modal Add Button -->
<button type="button" style="display:none;" id="modal_toggle" class="btn btn-primary" data-toggle="modal"
    data-target="#exampleModal">
    Launch demo modal
</button>
<!-- Modal Add-->
<div class="modal fade" id="exampleModal" tabindex="-1" style="margin-top:300px;" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1>Add Booking</h1>
                <form action="" method="post">
                    <label for="datetime">Date</label>
                    <input type="date" name="date" id="datetime" class="regular-text" required>

                    <label for="time">Time</label>
                    <input type="time" name="time" id="time" class="regular-text" required>

                    <label for="service">Service</label>
                    <input type="number" name="service_id" id="service" class="regular-text" required>

                    <label for="customer_id">Customer ID</label>
                    <input type="number" name="customer_id" id="customer_id" class="regular-text" required>

                    <label for="provider_id">Provider ID</label>
                    <input type="number" name="provider_id" id="provider_id" class="regular-text" required>

                    <label for="status">Status</label>
                    <select name="status" id="status" required="required">
                        <option value="pending">Pending
                        </option>
                        <option value="approved">Approved
                        </option>
                        <option value="rescheduled">Rescheduled
                        </option>
                        <option value="cancelled">Cancelled
                        </option>
                    </select>

                    <label for="notes">Notes</label>
                    <textarea name="notes" id="notes"></textarea>
                    <input type="hidden" name="field_id" value="0">

                    <?php wp_nonce_field(''); ?>
                    <button name="submit_booking" type="submit">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal View Button -->
<button type="button" style="display:none;" id="modal_view_toggle" class="btn btn-primary" data-toggle="modal"
    data-target="#exampleModalView">
    Launch demo modal
</button>
<!-- Modal View-->
<div class="modal fade" id="exampleModalView" tabindex="-1" style="margin-top:300px;" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ModalView</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h1>Event Information</h1>
                <strong>Date: </strong><p id="event_date"></p><br>
                <strong>Time: </strong><p id="event_time"></p><br>
                <strong>Customer: </strong><p id="event_customer"></p><br>
                <strong>Provider: </strong><p id="event_provider"></p><br>
                <strong>Service: </strong><p id="event_service"></p><br>
                <strong>Notes: </strong><p id="event_notes"></p><br>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>


<!-- FullCalendar Script -->
<script>
    (function($) {
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo $tempjson; ?> ,
                editable: true,
                selectable: true,
                select: function(start, end, jsEvent, view) {
                    document.getElementById('datetime').value = (moment(start.start).add(1, 'days')
                        .toISOString().split("T")[0]);
                    document.getElementById('modal_toggle').click();
                },
                eventClick: function(info){
                    document.getElementById('modal_view_toggle').click();
                    var event_info = moment(info.event.start).format('YYYY-MM-DD hh:mm:ss');
                    var event_date = event_info.toString().slice(0,10);
                    var event_time = info.event.start.toString().slice(16, 24);
                    var obj = info.event.extendedProps;
                    var event_customer = JSON.stringify(obj.customer_id).slice(0, -1).substring(1);
                    var event_provider = JSON.stringify(obj.provider_id).slice(0, -1).substring(1);
                    var event_service = JSON.stringify(obj.service_id).slice(0, -1).substring(1);
                    document.getElementById('event_date').innerHTML = event_date;
                    document.getElementById('event_time').innerHTML = event_time;
                    document.getElementById('event_customer').innerHTML = event_customer;
                    document.getElementById('event_provider').innerHTML = event_provider;
                    document.getElementById('event_service').innerHTML = event_service;
                    document.getElementById('event_notes').innerHTML = info.event.title;                                        
                },
                eventDrop: function(info, event, oldEvent) {                    
                    var selected_prev_date_time = info.oldEvent.start;
                    var selected_prev_time = selected_prev_date_time.toString().slice(16, 24);                     
                    var selected_date_time = moment(info.event.start).format('YYYY-MM-DD hh:mm:ss');
                    var selected_date = selected_date_time.slice(0, 11);
                    var selected_time = selected_date_time.slice(11, 20);                    
                    var selected_date_and_time = selected_date + selected_prev_time;
                    var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
                    var id = info.event.id;
                    var dragged_date = selected_date_and_time;
                    $.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: {
                            action: 'drag_date',
                            date: dragged_date,
                            id: id
                        },
                        success: function(data) {
                            calendar.refetchEvents();
                        },
                        error: function(xhr, status, error) {
                            alert("fail");
                        }
                    });

                }
            });
            calendar.render();
        });
    })(jQuery);
</script>
<?php