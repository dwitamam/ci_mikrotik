<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href=" <?php echo base_url() ?> " class="navbar-brand">Mikrotik</a>
            
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
        <li><a href="<?php echo base_url().'index.php/hotspotController' ?>" class="navbar-brand">Hotspot Users</a></li>
        <li class="dropdown"><a href="<?php echo base_url().'index.php/blokSitus' ?>" class="navbar-brand dropdown-toggle dropdown" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Block Website</a>
        <ul class="dropdown-menu">
                <li><a href="<?php echo base_url().'index.php/blokSitus' ?>" class="navbar-brand">Mangle</a></li>
                <li><a href="<?php echo base_url().'index.php/filterRules'?>" class="navbar-brand">Filter Rules</a></li>
            </ul>
    </li>
            
        <li><a href="<?php echo base_url().'index.php/BwController' ?>" class="navbar-brand" >Bandwidth</a></li>
        <li><a href="<?php echo base_url().'index.php/HistoryLog' ?>" class="navbar-brand">History</a></li> 
        </ul>
        </div>
    </div>
</nav>
<style>
    @media only screen and (min-width: 768px) {
  .dropdown:hover .dropdown-menu {
    display: block;
  }
</style>
