<div class="page-header">
    <h1>Filter Rules</h1>
    <a href="<?php echo base_url().'index.php/hotspotController/userAktif' ?>"><button class="btn btn-primary" type="button">User Aktif</button></a>
    <hr>
    <?php
        $flashmessage = $this->session->flashdata('message');
        echo !empty($flashmessage) ? '<div class="alert alert-success alert-dismissible" role="alert">'. $flashmessage. '</div>': '';
    ?>
    <p><?php echo !empty($total_result) ? "Terdapat $total_result User Hotspot" : ""; ?></p>

    <?php echo !empty($table)? $table : ''; ?>

    <p>
        <?php
            if(!empty($link)){
                foreach($link as $row){
                    echo $row;
                }
            } 
        ?>  
    </p>
</div>