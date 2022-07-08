<?php
if (isset($_POST['submit_booking'])) {
        global $wpdb;
        $table_name = $wpdb->prefix. 'kerberos';

        $date = $_POST['date'];
        $time = $_POST['time'];
        $service = $_POST['service'];
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
<h1>Add Booking</h1>
<form action="" method="post">
    <label for="datetime">Date</label>
    <input type="date" name="date" id="datetime" class="regular-text" required>

    <label for="time">Time</label>
    <input type="time" name="time" id="time" class="regular-text" required>

    <label for="service">Service</label>
    <input type="number" name="service" id="service" class="regular-text" required>

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
    <button name="submit_booking" type="submit">Submit</button>
</form>
<?php