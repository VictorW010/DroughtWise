<?php
if($_GET['tag'] && !empty($_GET['tag']))
{
    $tag = $_GET['tag'];
}
?>
<form action="" method="get">
    <select name="tag">
        <option value="" disabled selected>Filter by tag</option>
        <option value ="all">All</option>
        <option value="Business">Business Support</option>
        <option value="Farm">Farm Support</option>
        <option value="Family">Family Support</option>
        <option value="Community">Community Support</option>
    </select>
    <br></br>
    <button type="submit" class="bn3">Filter</button>
</form>
<br></br>
<?php
global $wpdb;
$max_col = 3;
if($tag == "all" || empty($tag)){
    $results = $wpdb->get_results( "SELECT * FROM wp_services");
}
else{
    $query = "SELECT * FROM wp_services WHERE s_tags like %s";
    $results = $wpdb->get_results($wpdb->prepare($query, '%'.$tag.'%') );
} ?>


<ul class="card-wrapper">
    <?php
    foreach ($results as $print ) { ?>
        <li class="card">
            <a href="<?php echo $print->s_href;?>" target="_blank">
                <img class="cardimg" src="<?php echo $print->s_pic_src;?>" alt=''></a>
            <h3><a href="<?php echo $print->s_href;?>" target="_blank"><?php echo $print->s_name;?></a></h3>
        </li>
        <?php
    }
    ?>
</ul>