<?php $balance = $this->requestAction('managers/getBalance/'.$this->Session->read('User.manager_id'));?>
<?php
	$balance = number_format($balance, 0, '.', ',');
?>
<h1 class = 'manager-h1 gold light'>Spend all your money! - Your balance is $<?php echo $balance;?></h1>

<div class = 'managers-index'>
	<div class = 'managers-header'>
    	<div class = 'feed-items-200 left'>Title</div>
        <div class = 'three-panel-550 left'>Description</div>
        <div class = 'three-panel-100 left'>Price</div>
        <div class = 'three-panel-100 left'>Action</div>
	</div>
    
   
	<?php $count = 1;?>
    <?php foreach($items as $item){?>
        <?php if (($count % 2) == 1){?>
                <div class = 'belts-each-item color1'>
        <?php }else{?>
                <div class = 'belts-each-item color2'>
        <?php }?>
        <?php 
			$item['Item']['price'] = number_format($item['Item']['price'], 0, '.', ',');
		
		?>
        		 <?php if(isset($item['ManagerItem']['0'])){?>
					 <?php if($item['ManagerItem']['0']['manager_id'] == $this->Session->read('User.manager_id')){?>
                        <div class = 'items-overlay'><div class = 'text-rotate'>Owned!</div>
                   
                            <div class = 'feed-items-200 left  items-padding'><?php echo $item['Item']['title']?></div>
                            <div class = 'three-panel-550 left  items-padding'><?php echo $item['Item']['description']?></div>
                            <div class = 'three-panel-100 left items-padding'><?php echo '$'.$item['Item']['price']?></div>
                            <div class = 'three-panel-100 left items-padding'></div>
               		  </div>
					   <?php }?>
                <?php }else {?>
                			<div class = 'feed-items-200 left  items-padding'><?php echo $item['Item']['title']?></div>
                            <div class = 'three-panel-550 left  items-padding'><?php echo $item['Item']['description']?></div>
                            <div class = 'three-panel-100 left items-padding'><?php echo '$'.$item['Item']['price']?></div>
                            <div class = 'three-panel-100 left items-padding'><a class = 'gold' href = '/manager_items/buy/<?php echo $item['Item']['id']?>/<?php echo $this->Session->read('User.manager_id')?>'>Buy!</a></div>
                
                <?php } ?>
                
                </div>
                
                      
   
        <?php $count++;?>        
    <?php }?>
    <div class = 'managers-footer'></div>
</div>