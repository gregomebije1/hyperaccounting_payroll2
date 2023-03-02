<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\FuelTank;
use common\models\FuelPump;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $model common\models\PetrolstationProductLoad */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="petrolstation-product-load-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-sm-3">
           <?= $form->field($product, 'pid_number')->textInput()?>
            <?= $form->field($product,  'truck_number')->textInput()?>       
        </div>
        <div class="col-sm-3">
            <?= $form->field($product,  'meter_ticket')->textInput()?>
            <?= $form->field($product,  'deport_loaded')->textInput()?>
        </div>
        <div class="col-sm-3">
             <?= $form->field($product,  'note')->textarea(['rows' => 5])?>
             <?= $form->field($product, "discharge_date")->hiddenInput([
                                "value" => $date])->label("")?>
        </div>
    </div>
         
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
              <th>Source Tank</th>
              <th>Tank opening</th>
              <th>New product quantity</th>
              <th>Total product available</th>
              <th>Tank closing</th>
              <th>Day sales</th>
              <th>Pump sales</th>
              <th>Difference</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            foreach ($productLoads as $index => $productLoad) {
            ?>  
                <tr>
                  <td>  
                   <?= $form->field($productLoad, "[$index]fuel_tank_id")->dropDownList(
                        ArrayHelper::merge([""=>""], ArrayHelper::map(FuelTank::find()->all(), 'id', 'name')),
                        ['class' => 'form-control',  "onChange" => "getTankBalance($index)"])->label('');
                    ?>
                  </td>
                  <td>
                      <?= $form->field($productLoad, "[$index]tank_opening")->textInput([
                          "readonly" => "readonly"])->label("") ?>
                  </td>
                  <td>
                      <?= $form->field($productLoad, "[$index]quantity")->textInput([
                          "onChange" => "createProduct($index)"])->label("") ?>  
                  </td>
                  <td>
                      <?= $form->field($productLoad, "[$index]total_product_available")->textInput([
                          "readonly" => "readonly"])->label("") ?>
                  </td>
                  <td>
                      <?= $form->field($productLoad, "[$index]tank_closing")->textInput([
                          "onchange" => "calculateDaySales($index)"])->label("")?>
                  </td>
                  <td>
                      <?= $form->field($productLoad, "[$index]day_sales")->textInput([
                          "readonly" => "readonly"])->label("") ?>
                  </td>
                  <td>
                      <?= $form->field($productLoad, "[$index]pump_sales")->textInput([
                          "readonly" => "readonly", "onchange" => "calculateDifference($index)"])->label("")?>
                  </td>
                  <td style="width:7%">
                      <?= $form->field($productLoad, "[$index]difference")->textInput(
                              ["readonly" => "readonly"])->label("") ?>
                  </td>
                </tr>
            <?php 
            }
            ?>
      </tbody>
    </table>
    <div class="form-group">
        <?= Html::submitButton($product->isNewRecord ? 'Create' : 'Update', ['class' => $product->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
<script>
    function getTankBalance(index) {
        $(document).ready(function(){
        //$("#petrolstationproductload-" + index + "-fuel_tank_id").change(function(){
            var productLoad = $("#petrolstationproductload-" + index + "-fuel_tank_id");
            var optionSelected = $(productLoad).find('option:selected');
            var optValueSelected = optionSelected.val();
            $.get("get-tank-balance?id=" + optValueSelected, function(data, status){                   
                response = JSON.parse(data);
                if(response.success == 1) {
                    $("#petrolstationproductload-" + index + "-tank_opening").val(response.balance);
                } else {
                    alert(response.message);
                }
            });
            getPumps(index);
        });
        /*
        var stockAvailable = Number.parseFloat($("#fuelloadtank2-" + index + "-tank_opening_stock").val())
              + Number.parseFloat($("#fuelloadtank2-" + index +"-quantity_received").val());
                $("#fuelloadtank2-" + index +"-stock_available").val(stockAvailable);
        });
       */
    }
    function getPumps(index) {
        $(document).ready(function(){
            var productLoad = $("#petrolstationproductload-" + index + "-fuel_tank_id");
            var optionSelected = $(productLoad).find('option:selected');
            var optValueSelected = optionSelected.val();

            $.get("get-pumps?fuelTankId=" + optValueSelected, function(data, status){                   
                response = JSON.parse(data);
                var select = $("#petrolstationproductload-" + index + "-fuel_pump_id");
                select.empty();   

                select.append('<option value=""></option>');
                if(response.success == 1) {
                    //iterate over the data and append a select option
                    $.each(response.data, function(key, val){ 
                        select.append('<option value="' + val.id + '">' + val.name + '</option>');
                    });
                } else {
                   alert(response.message);
                }
            });
        });
    }
    function createProduct(index) {
        var quantity = $("#petrolstationproductload-" + index + "-quantity").val();      
        var prdAvail = parseFloat($("#petrolstationproductload-" + index + "-tank_opening").val());
        prdAvail += parseFloat(quantity);     
        $("#petrolstationproductload-" + index + "-total_product_available").val(prdAvail);
    }
    function calculateDaySales(index) {
        var prdAvail = parseFloat($("#petrolstationproductload-" + index + "-total_product_available").val());
        var tankClosing = parseFloat($("#petrolstationproductload-" + index + "-tank_closing").val());
        $("#petrolstationproductload-" + index + "-day_sales").val(prdAvail + tankClosing);
    }
    function calculateDifference(index) {
        var pumpSales = parseFloat($("#petrolstationproductload-" + index + "-pump_sales").val());
        var daySales = parseFloat($("#petrolstationproductload-" + index + "-day_sales").val());
        $("#petrolstationproductload-" + index + "-difference").val(daySales - pumpSales);
    }
</script>
<?php 
$script = <<<EOD
    var a = 1;
EOD;
 $this->registerJs($script);
 ?>
                             