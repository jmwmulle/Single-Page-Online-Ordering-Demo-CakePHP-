<?php echo $this->set('title_for_layout', 'Delivery Information'); ?>

<?php $this->Html->addCrumb('Address'); ?>

<?php echo $this->Html->script(array('shop_address.js'), array('inline' => false)); ?>

<h1>Address</h1>

<?php echo $this->Form->create('Order'); ?>

<hr>

<div class="row">
<div class="col col-sm-4">

<?php echo $this->Form->input('first_name', array('class' => 'form-control', 'default' => $user['User']['firstname'])); ?>
<br />
<?php echo $this->Form->input('last_name', array('class' => 'form-control', 'default' => $user['User']['lastname'])); ?>
<br />
<?php echo $this->Form->input('email', array('class' => 'form-control', 'default' => $user['User']['email'])); ?>
<br />
<?php echo $this->Form->input('phone', array('class' => 'form-control', 'default' => 'This is a test')); ?>
<br />
<br />

</div>
<div class="col col-sm-4">

<?php echo $this->Form->input('billing_address', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('billing_address_2', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('billing_city', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('billing_province', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('billing_postal_code', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('billing_country', array('class' => 'form-control')); ?>
<br />
<br />

<?php echo $this->Form->input('sameaddress', array('type' => 'checkbox', 'label' => 'Copy Billing Address to Delivery?')); ?>

</div>
<div class="col col-sm-4">

<?php echo $this->Form->input('delivery_address', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('delivery_address_2', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('delivery_city', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('delivery_postal_code', array('class' => 'form-control')); ?>
<br />
<br />

</div>
</div>

<br />

<?php echo $this->Form->button('Continue', array('class' => 'btn btn-default btn-primary'));?>
<?php echo $this->Form->end(); ?>

