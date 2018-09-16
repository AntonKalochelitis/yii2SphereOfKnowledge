<aside class="main-sidebar">

    <section class="sidebar">

        <?php // echo \frontend\widgets\MenuLeftUserWidget::widget(['directoryAsset' => $directoryAsset]) ?>

        <?= \frontend\widgets\MenuLeftSearchFormWidget::widget(['directoryAsset' => $directoryAsset]) ?>

        <?= \frontend\widgets\MenuLeftWidget::widget(); ?>

    </section>

</aside>
