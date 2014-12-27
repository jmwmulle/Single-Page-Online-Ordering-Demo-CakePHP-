<?php echo $this->set('title_for_layout', 'Delivery Information'); ?>

<?php $this->Html->addCrumb('Address'); ?>

<?php echo $this->Html->script(array('shop_address.js'), array('inline' => false)); ?>

<h1>Address</h1>

<?php echo $this->Form->create('Order'); ?>

<hr>

<div class="row">
<div class="col col-sm-4">

<?php if ($loggedIn): ?>
<?php echo $this->Form->input('firstname', array('class' => 'form-control', 'default' => $User['firstname'])); ?>
<br />
<?php echo $this->Form->input('lastname', array('class' => 'form-control', 'default' => $User['lastname'])); ?>
<br />
<?php echo $this->Form->input('email', array('class' => 'form-control', 'default' => $User['email'])); ?>
<br />
<?php echo $this->Form->input('phone', array('class' => 'form-control')); ?>
<br />
<br />

</div>
<div class="col col-sm-4">

<?php echo $this->Form->input('billing_address', array('class' => 'form-control', 'default' => $User['address'])); ?>
<br />
<?php echo $this->Form->input('billing_address_2', array('class' => 'form-control', 'default' => $User['address_2'])); ?>
<br />
<?php echo $this->Form->input('billing_city', array('class' => 'form-control', 'default' => $User['city'])); ?>
<br />
<?php echo $this->Form->input('billing_province', array('class' => 'form-control', 'default' => $User['province'])); ?>
<br />
<?php echo $this->Form->input('billing_postal_code', array('class' => 'form-control', 'default' => $User['postal_code'])); ?>
<br />
<?php echo $this->Form->input('billing_country', array('class' => 'form-control', 'default' => 'Canada')); ?>
<br />
<br />

<?php else: ?>

<?php echo $this->Form->input('firstname', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('lastname', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('email', array('class' => 'form-control')); ?>
<br />
<?php echo $this->Form->input('phone', array('class' => 'form-control')); ?>
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
<?php endif; ?>


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

