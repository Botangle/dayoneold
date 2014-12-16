<?php
/**
 * _siginin.blade.php
 *
 * @author: Martyn Ling <mling@str8-4ward.com>
 * Date: 8/25/14
 * Time: 10:56 AM
 */
?>
<div class="span3 PageRight-Block">
    <p class="FontStyle20">{{ trans('Already a member? Sign In here') }}</p>
    <p>{{ trans('Click here to sign In to Botangle!') }}</p><br>
    <br>
    {{ Html::link(route('login'), trans('Sign In'), array('class' => "btn btn-primary")) }}
</div>