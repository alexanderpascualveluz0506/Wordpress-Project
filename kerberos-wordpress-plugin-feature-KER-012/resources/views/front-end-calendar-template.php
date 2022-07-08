<?php
global $wpdb;
    $results = $wpdb->get_results('SELECT id, datetime, notes FROM '.$wpdb->prefix.'kerberos');
    foreach ($results as $event) {
        $events[] = $event;
    }
    $tempjson = json_encode($events);
    $tempjson = str_replace('"datetime"', 'date', $tempjson);
    $tempjson = str_replace('"notes"', 'title', $tempjson);

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
    } ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: <?php echo $tempjson; ?> ,
            selectable: true,
            select: function(start, end, jsEvent, view) {
                document.getElementById('datetime').value = (moment(start.start).toISOString()
                    .split("T")[0]);
                $('#modal_toggle').click();
            }
        });
        calendar.render();
    });
</script>
<div id='calendar'></div>
<button type="button" style="display:none;" id="modal_toggle" class="btn btn-primary" data-toggle="modal"
    data-target="#exampleModal">
    Launch demo modal
</button>
<!-- Modal -->
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
<?php