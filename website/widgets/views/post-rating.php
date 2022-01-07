<div class='rating-stars' id='<?=$sectionId;?>'>
    <h5>Is this article useful for you?</h5>
    <div class="d-flex align-items-center">
        <h3 class="mb-0 mr-2" id='user-rating'><?=$stars;?></h3>
        <ul class="mb-0" id='post-stars'>
            <?php for ($i = 1; $i <= 5; $i++) : ?>
            <li class='star <?=($i <= $stars) ? "selected" : "";?>' data-value='<?=$i;?>'>
                <span class="icon-star"></span>
            </li>
            <?php endfor;?>
        </ul>
        <span class="ml-2">[Total:</span><span id='total-rating'> <?=$total;?></span>
        <span class="ml-2">Average: </span><span id='average-rating'> <?=$average;?></span>]
    </div>
</div>