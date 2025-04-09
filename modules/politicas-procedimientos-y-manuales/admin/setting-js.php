<script>
    console.log('setting-js.php');
    ARCH_API_URL                    ="<?=ARCH_API_URL?>";    

    ARCH_FIRST_LV_CATEGORY_TABLE    ="<?=ARCH_FIRST_LV_CATEGORY_TABLE?>";
    ARCH_SECOND_LV_CATEGORY_TABLE   ="<?=ARCH_SECOND_LV_CATEGORY_TABLE?>";
    ARCH_THIRD_LV_CATEGORY_TABLE    ="<?=ARCH_THIRD_LV_CATEGORY_TABLE?>";
    ARCH_FOUR_LV_CATEGORY_TABLE     ="<?=ARCH_FOUR_LV_CATEGORY_TABLE?>";
    ARCH_FIVE_LV_CATEGORY_TABLE     ="<?=ARCH_FIVE_LV_CATEGORY_TABLE?>";
    ARCH_VIEW_ALL_CATEGORIES_TABLE  ="<?=ARCH_VIEW_ALL_CATEGORIES_TABLE?>";
    
    ARCH_URI_BASE                   ="<?=ARCH_URI_BASE?>";

<?php if ( is_path('post_id', 3, false) ) : ?>

    ARCH_POST_DATA_TABLE            ="<?=ARCH_POST_DATA_TABLE?>";        

<?php endif; ?>

    
</script>