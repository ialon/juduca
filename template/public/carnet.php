<div class="carnet-wrapper html2pdf__page-break id-<?php echo $this->data['bookingid'] . ' ' . $this->data['color'] . ' ' . $this->data['caps-fixed'] . ' ' . $this->data['unishortname']; ?>">
    <div class="carnet-content">
        <div class="carnet-header">
            <div class="carnet-country">
                <?php echo $this->data['carnet-photo']; ?>
                <div class="carnet-country-label <?php echo $this->data['countrycode']; ?>"><?php echo $this->data['country']; ?></div>
                <div class="carnet-country-flag <?php echo $this->data['countrycode']; ?>"></div>
                <div class="carnet-type"><?php echo $this->data['type']; ?></div>
                <?php echo $this->data['sportlogo']; ?>
            </div>
        </div>
        <div class="carnet-main">
            <div class="carnet-names"><?php echo $this->data['nombres']; ?></div>
            <div class="carnet-lastnames"><?php echo $this->data['apellidos']; ?></div>
            <div class="carnet-unilogo"><img alt="<?php echo $this->data['universidad']; ?>" src="<?php echo $this->data['universidadlogo']; ?>"/></div>
        </div>
        <div class="carnet-footer">
            <div class="carnet-qr-code"><?php echo $this->data['qrcode']; ?></div>
        </div>
    </div>
</div>
<?php
if ($this->data['includeback']) {
    echo '<div class="carnet-wrapper back ' . $this->data['color'] . '"></div>';
}
?>
