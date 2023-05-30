<link href="https://fonts.googleapis.com/css?family=Poppins:100,100italic,200,200italic,300,300italic,400,400italic,500,500italic,600,600italic,700,700italic,800,800italic,900,900italic" rel="stylesheet" type="text/css">
<div class="carnet-wrapper <?php echo $this->data['color']; ?>" style="background-size: cover; background-color: #FFFFFF; background-image: url('http://juduca.local/wp-content/uploads/2023/05/Screen-Shot-2023-05-30-at-12.35.29-e1685475571175.png');">
    <div class="carnet-content">
        <div class="carnet-header">
            <div class="carnet-photo"><img src="<?php echo $this->data['urlfoto']; ?>"/></div>
            <div class="carnet-country">
                <span class="carnet-country-label">Honduras</span>
                <img class="carnet-country-flag" src="<?php echo $this->data['urlfoto']; ?>"/>
                <div class="carnet-type">Atleta</div>
                <img class="carnet-sport" alt="<?php echo $this->data['disciplina']; ?>" src="<?php echo $this->data['urldisciplina']; ?>"/>
            </div>
        </div>
        <div class="carnet-main">
            <div class="carnet-names"><?php echo $this->data['nombres']; ?></div>
            <div class="carnet-lastnames"><?php echo $this->data['apellidos']; ?></div>
            <div class="carnet-unilogo"><img alt="<?php echo $this->data['universidad']; ?>" src="<?php echo $this->data['universidadlogo']; ?>"/></div>
        </div>
        <div class="carnet-footer">
            <div class="carnet-qr-code"></div>
            <div class="carnet-amenities"></div>
        </div>
    </div>
</div>
