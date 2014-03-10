<div class="container text-center">
    <h1>What do you need help with?</h1>

    <div class="Searchblock row-fluid">
        <form class="form-search">
            <div class="Search-main1">
                <input type="text" class="input-medium search-query" value="Example: Chemistry, Maths etc">
            </div>
            <div class="Search-main1-btn">
                <button type="submit" class="btn search-main-btn">Search</button>
            </div>
        </form>
    </div>
    <div class="joinus">
        <div class="title"> Join Us</div>
        <div class=" row-fluid join-btn-block">
            <div class="span6 joinus-button1"> <?php  echo $this->Html->link(
                __('Become a Student'),
                array(
                'controller' => 'user',
                'action' => 'registration',
                ) ,
                array('class'=>'join-btn')
                );?>
            </div>
            <div class="span6 joinus-button1"><?php  echo $this->Html->link(
                __('Become a Tutor'),
                array(
                'controller' => 'user',
                'action' => 'registration',
                'full_base' => true,
                ) ,
                array('class'=>'join-btn')
                );?>
            </div>
        </div>
    </div>
</div>