<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Update Employee: ' . $model->firstname . ' ' . $model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<ul class="nav nav-tabs">
  <li><a href="<?=Url::toRoute(['employee/update', 'id' => $model->id])?>">Employee</a></li>
  <li><a href="<?=Url::toRoute(['employee-deductions-allowances/update', 'type' => 'Allowances', 'employeeId' => $model->id])?>">Allowances</a></li>
  <li><a href="<?=Url::toRoute(['employee-deductions-allowances/update', 'type' => 'Deductions', 'employeeId' => $model->id])?>">Deductions</a></li>
  <li class="active"><a href="<?=Url::toRoute(['employee/payee', 'id' => $model->id])?>">Payee</a></li>
</ul>
<?php
  
  $total_benefits = 0;
  $total_payee = 0;
  $total_reliefs_exemptions = 0;
  $basic = 0;
  $housing = 0;
  $transport  = 0;
  $paye_per_annum = 0;
  
  $salary = $model->getSalary();
  $annual_salary = $salary * 12;
  
  $allowance = $model->getLeaveAllowance();
  $annual_allowance = $allowance * 12;

  $payee_items = $model->getPayeeItems('paye');
  
  $benefits_in_kind = $model->getBenefitsInKind();
  
  $relief_items = $model->getPayeeItems('reliefs');
  $exemptions_items = $model->getPayeeItems('exemptions');
  
?>
<div class="table-responsive">
  <table class="table table-striped table-bordered table-hovertable-hover"> 
   <tr class='class1'>
    <td colspan='4'><h3><?= $model->firstname . ' ' . $model->lastname?> - PAYEE</h3></td>
   </tr>
   <tr>
    <th></th>
	<th>Percentage</th>
	<th>Monthly</th>
	<th>Annual</th>
   </tr>
   <tr>
    <td>SALARY</td>
	<td></td>
	<td><?= number_format($salary, 2); ?></td>
	<td><?php echo number_format($annual_salary, 2); ?></td>
   </tr>
   <tr>
	<td>LEAVE ALLOWANCE</td>
	<td></td>
	<td><?php echo number_format($allowance, 2); ?></td>
	<td><?php echo number_format($annual_allowance, 2); ?></td>
   </tr>
   
   <tr>
    <th colspan='4' >PAYE</th>
   </tr>
   
   <?php
   foreach($payee_items as $payee_item) {
   
     echo "<tr>
	   <td>{$payee_item['name']}</td>";
	 echo "<td>" . $payee_item['amount'];
	 echo ($payee_item['payee_item_type'] == 'percentage') ? '%' : '' . " </td>";
	 echo "
	   <td></td>
	   <td>";
	 $temp = $payee_item['payee_item_type'] == 'percentage' ? $payee_item['amount']/100 : $payee_item['amount'];
	 if ($payee_item['name'] == 'BASIC')
	   $basic = $annual_salary * $temp;
	 else if ($payee_item['name'] == 'HOUSING')
	   $housing = $annual_salary * $temp;
	 else if ($payee_item['name'] == 'TRANSPORT')
	   $transport = $annual_salary * $temp;
	   
     echo number_format($annual_salary * $temp);
     $total_payee += ($annual_salary * $temp);
      echo "<td>
	  </tr>";
	 
  }
  ?>
  <tr>
   <td></td>
   <td style='font-weight:bold;'>100%</td>
   <td></td>
  </tr>
  
  <tr>
   <td>LEAVE ALLOWANCE (10% of annual basic)</td>
   <td></td><td></td>
   <td><?php echo number_format($annual_allowance, 2); ?></td>
  </tr>
  
  <tr>
   <th colspan='4'>BENEFITS IN KIND</th>
  </tr>
  
  <?php
   foreach($benefits_in_kind as $benefits_in_kind) {
     echo "<tr>
           <td>{$benefits_in_kind['name']}</td>
	   <td>" . number_format($benefits_in_kind['amount'], 2) . "</td>
	   <td></td>
	  </tr>";
	 $total_benefits += $benefits_in_kind['amount'];
  }
  ?>
  <tr>
   <td></td>
   <td></td>
   <td></td>
   <td><?php echo number_format($total_benefits, 2); ?></td>
  </tr>
  <tr>
   <th colspan='3'>GROSS INCOME</th>
   <th>
   <?php
   $gross_income = $total_payee + $annual_allowance + $total_benefits;
   echo number_format($gross_income, 2);
   ?>
   </th>
  </tr>   
  
  <tr><td colspan='4'></td></tr>
  
  <tr><th colspan='4'>RELIEFS</th></tr>
  <?php
   foreach($relief_items as $relief_item) {
     echo "<tr>
	   <td>{$relief_item['name']}</td>";
	 echo "<td>" . $relief_item['amount'];
	 echo ($relief_item['payee_item_type'] == 'percentage') ? '%' : '' . " </td>";
	 echo "
	   <td>";
	 if ($relief_item['name'] == 'PERSONAL ALLOWANCE')
	   $temp = ($gross_income * ($relief_item['amount']/100)) + 200000;
	 else {
	   $temp = $relief_item['payee_item_type'] == 'percentage' ? $relief_item['amount']/100 : $relief_item['amount'];
	 }
	 $total_reliefs_exemptions += $temp;
     echo number_format($temp, 2) . "<td>
	  </tr>";
  }
  ?>
  <tr><th colspan='4'>EXEMPTIONS</th></tr>
  <?php
   foreach($exemptions_items as $exemption_item) {
     echo "<tr>
	   <td>{$exemption_item['name']}</td>";
	 echo "<td>" . $exemption_item['amount'];
	 echo ($exemption_item['payee_item_type'] == 'percentage') ? '%' : '' . " </td>";
	 echo "
	   <td> ";
	 if ($exemption_item['name'] == 'NHF') 
	   $temp = ($exemption_item['amount']/100) * $basic;
	 else if ($exemption_item['name'] == 'PENSION') 
	     $temp = ($exemption_item['amount']/100) * ($basic + $housing + $transport);
	 else 
	   $temp = $exemption_item['payee_item_type'] == 'percentage' ? $exemption_item['amount']/100 : $exemption_item['amount'];
	 
	 $total_reliefs_exemptions += $temp;
     echo number_format($temp, 2) . "<td>
	  </tr>";
  }
  ?>
  <tr>
   <th>TAX FREE PAY</th>
   <th></th>
   <th></th>
   <th><?php echo number_format($total_reliefs_exemptions, 2); ?></th>
  </tr>
  <tr>
   <th>TAXABLE INCOME</th>
   <th></th>
   <th></th>
   <th>
    <?php 
	  $taxable_income = $gross_income - $total_reliefs_exemptions;
      echo number_format($taxable_income, 2); 
	?>
   </th>
  <tr><th colspan='4'>PAYE</th></tr>
  
  <?php
   //If taxable income is negative use 1%
   if ($taxable_income <= 0)
     $annual_paye = (1/100) * $taxable_income;
   
   $arr = array(1 => array('7','300000'), 
                2 => array('11', '300000'), 
				3 => array('15', '500000',),
				4 => array('19', '500000',),
				5 => array('21', '1600000',),
				6 => array('24', '3200000'));
   $bal = 0;
   foreach($arr as $key => $value) {
     if ($value[0] == '24')
	  echo "<tr><td></td><td>" . number_format($taxable_income, 2) . "</td><td></td><td></td></tr>";
	
     echo "<tr>";
     if ($value[0] == '7') {
	   $bal = $taxable_income;
      echo "<td>1ST</td>";
	 } else
	   echo "<td>NEXT</td>";
	   
	  
	 //echo "before: n={$next_bal} b={$bal} v={$value[1]}<Br>";
	 
	 $bal = $bal - $value[1];
	 
	 //echo "after: n={$next_bal} b={$bal} v={$value[1]}<Br>";
	 
	 if (($key + 1) <= count($arr))
	   $next_bal = $bal - $arr[$key + 1][1]; //Next balance
	 else
	   $next_bal = 0;
	   
	 if (($value[0] == 7) && ($bal <= 0)) {
	   $paye_per_annum += ($taxable_income * ($value[0]/100));
	  echo "
	    <td>" . number_format($taxable_income, 2) . "</td>
        <td>" . $value[0] . "%</td> 
	    <td>" . number_format($taxable_income * ($value[0]/100), 2) . "</td>
       </tr>";
	   $bal = 0;
	 } else if ($next_bal <= 0) {
	   $paye_per_annum += (($bal + $value[1])* ($value[0]/100));
	   
	   echo "
	    <td>" . number_format($bal + $value[1], 2) . "</td>
        <td>" . $value[0] . "%</td> 
	    <td>" . number_format(($bal + $value[1])* ($value[0]/100), 2) . "</td>
       </tr>";
	   $bal = 0;
     } else {
	   $paye_per_annum += ($value[1] * ($value[0]/100));
       echo "	 
	     <td>" . number_format($value[1], 2) . "</td>
         <td>" . $value[0] . "%</td> 
	    <td>" . number_format($value[1] * ($value[0]/100), 2) . "</td>
       </tr>";
     }
   }
  ?>
  <tr>
   <th>PAYE PER ANNUM</th>
   <th></th>
   <th></th>
   <th>
   <?php 
    echo number_format($paye_per_annum, 2); 
    if ($paye_per_annum < 0)
	 echo "&nbsp;&nbsp;<span style='color:red;'>" . number_format((1/100) * $annual_salary, 2) . "</span>";
   ?>
   </th>
  </tr>
  <tr>
   <th>PER MONTH</th>
   <th></th>
   <th></th>
   <th>
   <?php 
    echo number_format($paye_per_annum/12, 2); 
	if ($paye_per_annum < 0)
	 echo "&nbsp;&nbsp;<span style='color:red;'>" . number_format((1/100) * $salary, 2) . "</span>";
	 ?>
	</th>
  </tr>
  
  <tr>
   <td>Effective Tax Rate</td>
   <td>
    <?php 
     if ($gross_income != 0)
        echo number_format(abs(($paye_per_annum/$gross_income)*(100/1)), 2) . "%"; 
	   if ($paye_per_annum < 0)
	     echo "&nbsp;&nbsp;<span style='color:red;'>1%</span>";
	?>
    </td>
  </tr>
  
 </table>
</div>

