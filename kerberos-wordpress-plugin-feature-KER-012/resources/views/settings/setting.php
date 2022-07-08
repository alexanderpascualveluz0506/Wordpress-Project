<?php
    global $wpdb;
    $select= $wpdb->get_results("SELECT * from  {$wpdb->prefix}settings");
    foreach($select as $value){
            $image=$value->icon;
            $closing_time=$value->closing_time;
            $opening_time=$value->opening_time;
    }

    $result= $wpdb->get_results("SELECT opening_days->'$.Sunday' as Sunday, opening_days->'$.Monday' as Monday,  opening_days->'$.Tuesday' as Tuesday, opening_days->'$.Wednesday' as Wednesday, opening_days->'$.Thursday' as Thursday, 
    opening_days->'$.Friday' as Friday, opening_days->'$.Saturday' as Saturday from wp_settings"); 
    foreach ($result as $a){ 
            $sunday=$a->Sunday;
            $monday=$a->Monday;
            $tuesday=$a->Tuesday;
            $wednesday=$a->Wednesday;
            $thursday=$a->Thursday;
            $friday=$a->Friday;
            $saturday=$a->Saturday;
    }
   ?>
   <form action="" method="POST">
    <h1>Settings</h1>
   
    
    <h3>Logo</h3>
    <div id="icon-div">


    <?php if (!empty($image)){ ?> 
           <img  src="<?php echo $image ?>" height=140 width=140 id="image-render1">       
        <?php } ?>    

    </div>

    <button class="upload_icon">Upload Logo</button>

    <input type="text" id="icon-url" name="icon_url" value="<?php echo $value->icon?>">

    <h3>Opening Days</h3>

        <input type="checkbox" id="vehicle1" name="days[]" value="0"  <?php if ($sunday=="true") {echo "checked";} ?>>
        <label for="vehicle1">Sunday</label><br>

        <input type="checkbox" id="vehicle2" name="days[]" value="1" <?php if ($monday=="true") {echo "checked";} ?>>
        <label for="vehicle2">Monday</label><br>

        <input type="checkbox" id="vehicle3"  name="days[]" value="2"  <?php if ($tuesday=="true") {echo "checked";} ?>>
        <label for="vehicle3">Tuesday</label><br>
        
        <input type="checkbox" id="vehicle1"  name="days[]" value="3"  <?php if ($wednesday=="true") {echo "checked";} ?>>
        <label for="vehicle1">Wednesday</label><br>

        <input type="checkbox" id="vehicle2"  name="days[]" value="4" <?php if ($thursday=="true") {echo "checked";} ?>>
        <label for="vehicle2">Thursday</label><br>

        <input type="checkbox" id="vehicle3"  name="days[]" value="5" <?php if ($friday=="true") {echo "checked";} ?>>
        <label for="vehicle3">Friday</label><br>

        <input type="checkbox" id="vehicle3"  name="days[]" value="6" <?php if ($saturday=="true") {echo "checked";} ?>>
        <label for="vehicle3">Saturday</label><br><br>

        <h3>Opening Hours</h3>

        <label>FROM: </labe>
        <input type="time" name="opening_time" value="<?php echo $opening_time ?>" required>


        <label>TO: </labe>
        <input type="time" name="closing_time"  value="<?php echo $closing_time ?>" required>
        <br><br>


        <input type="submit" value="Submit" name="submit_setting">
        <?php wp_nonce_field(''); ?>

    </form>


    <?php
    global $wpdb; 
    $num_rows = $wpdb->get_var("SELECT COUNT(*) from {$wpdb->prefix}settings");
   
    if (isset($_POST['submit_setting'])){
        global $wpdb;
        $icon = isset($_POST['icon_url']) ? sanitize_text_field($_POST['icon_url']) : '';
        $opening_time = isset($_POST['opening_time']) ? sanitize_text_field($_POST['opening_time']) : '';
        $closing_time = isset($_POST['closing_time']) ? sanitize_text_field($_POST['closing_time']) : '';
        $days_name=array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

        $days = array();
        if (isset($_POST['days'])){
            for ($i = 0; $i <= 6; $i++) {    
                $name= $days_name[$i];
                $days[$name] = in_array($i, $_POST['days']) ? true : false;   
            }
        }else{
            foreach($days_name as $key=>$value ){
                $fallback=array(false,false,false,false,false,false,false);
                $val=$fallback[$key];
                $days[$value]=$val;
            }
        }
      
        $opening_days= json_encode($days);
      

       if ($num_rows==0){
        $query = $wpdb->insert(
            $wpdb->prefix . 'settings',
            array(
                'icon'=>$icon,
                'created_time'=>current_time('mysql'),
                'updated_time'=>current_time('mysql'),
                'closing_time' => $opening_time,
                'opening_time' => $closing_time,
                'opening_days'=> $opening_days  
            )
        );
       }else{
            $query = $wpdb->update(
                $wpdb->prefix . 'settings',
                array(
                    'icon'=>$icon,
                    'updated_time'=>current_time('mysql'),
                    'opening_time' => $opening_time,
                    'closing_time' => $closing_time,
                    'opening_days'=> $opening_days     
                ),
                array(
                    'id'=>1
                ),
            );
        }
        
        if (!$query) {
            echo "<div class='sub-failure'>Database error.</div>";			
        }else{
            echo "<p align='center'>Successful </b></p>";
        }
     
        wp_redirect( home_url() .'/wp-admin/admin.php?page=settings');
        exit;       
    }

   