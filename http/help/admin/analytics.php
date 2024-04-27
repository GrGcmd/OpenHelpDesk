<?php
require("../../data/header.php");
require("../../data/db.php");
require("../../data/session.php");
require("../../menu.php");
require("../../data/clean.php");

//Проверка на роль
if ($data['role']!='superadmin') {
  echo '<meta http-equiv="Refresh" content="0; url=/help/" />';
  exit();
}


// $select_date_start = strtotime($date)+10800;         
// $select_date_end = strtotime($date)+10800+86399;
// echo date("d.m.Y h:i:s A",time());
// $date='.date("d-m-Y", $data['create_date'])

$data_info = array(array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"),array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"));

$date = date("d.m.Y",time());
$select_date_start = strtotime($date);      
$select_date_end = $select_date_start+86399;

$j=0;
while ($j<30) {
  $query = mysqli_query($link, "SELECT count(id) AS bids_sum_from_date FROM bid WHERE date > ".$select_date_start ." and date < ".$select_date_end." ");
  $data = mysqli_fetch_assoc($query);
  $data_info[$j][0] = date("d-m", $select_date_start);
  $data_info[$j][1] = $data['bids_sum_from_date'];
  
  $select_date_start = $select_date_start - 86400;
  $select_date_end = $select_date_start + 86399;
  
  $j++;
}

//print_r ($data_info);

?>
<div class="row justify-content-center">
  <div class="col col-12 text-center">
    <h4>Аналитика</h4>
  </div>
</div>
</br>
<div class="container">
  <div class="row justify-content-md-center">
    <div class="col col-md-6 py-3 border border-info bg-light rounded-lg rounded">
      <div style="text-align:center;"><h4>За последний месяц всего</h4></div>
      </br>
      <div>
        <canvas id="bids_statistics"></canvas>
      </div>
      <script>
        const ctx = document.getElementById('bids_statistics');

        new Chart(ctx, {
          type: 'line',
          data: {
            labels: [<?php printf("'%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s'",$data_info[29][0],$data_info[28][0],$data_info[27][0],$data_info[26][0],$data_info[25][0],$data_info[24][0],$data_info[23][0],$data_info[22][0],$data_info[21][0],$data_info[20][0],$data_info[19][0],$data_info[18][0],$data_info[17][0],$data_info[16][0],$data_info[15][0],$data_info[14][0],$data_info[13][0],$data_info[12][0],$data_info[11][0],$data_info[10][0],$data_info[9][0],$data_info[8][0],$data_info[7][0],$data_info[6][0],$data_info[5][0],$data_info[4][0],$data_info[3][0],$data_info[2][0],$data_info[1][0],$data_info[0][0]) ?>],
            datasets: [{
              label: 'Новые заявки', 
              data: [<?php printf("%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s",$data_info[29][1],$data_info[28][1],$data_info[27][1],$data_info[26][1],$data_info[25][1],$data_info[24][1],$data_info[23][1],$data_info[22][1],$data_info[21][1],$data_info[20][1],$data_info[19][1],$data_info[18][1],$data_info[17][1],$data_info[16][1],$data_info[15][1],$data_info[14][1],$data_info[13][1],$data_info[12][1],$data_info[11][1],$data_info[10][1],$data_info[9][1],$data_info[8][1],$data_info[7][1],$data_info[6][1],$data_info[5][1],$data_info[4][1],$data_info[3][1],$data_info[2][1],$data_info[1][1],$data_info[0][1]); ?>],
              borderWidth: 3,
              borderColor: 'rgb(59, 132, 143)',
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            }
          }
        });
      </script>
    </div>
    <div class="col col-md-6 py-3 border border-info bg-light rounded-lg rounded">
      <div style="text-align:center;"><h4>За последний месяц по шаблонам</h4></div>
      </br>
      <?php

        $date = date("d.m.Y",time());
        $select_date_start = strtotime($date)-25920000;      
        $select_date_end = $select_date_start+25920000+86399;

        $query = mysqli_query($link, "SELECT count(id) AS all_bids FROM bid WHERE date > ".$select_date_start ." and date < ".$select_date_end." ");
        $data = mysqli_fetch_assoc($query);
        $all_bids = $data['all_bids'];
        
        $query_template = mysqli_query($link, "SELECT id,name FROM bid_tempates");
        echo '<div style="text-align:center;">Всего заявок: '.$all_bids.'</div></br>';
        while ($data_template = mysqli_fetch_assoc($query_template)) {
          
          $query = mysqli_query($link, "SELECT count(id) AS bids_sum_from_template FROM bid WHERE template='".$data_template['id']."' and date > ".$select_date_start ." and date < ".$select_date_end." ");
          $data = mysqli_fetch_assoc($query);
          echo '
          '.$data_template['name'].'
          <div class="progress" role="progressbar" aria-label="Example with label" aria-valuenow="'.$data['bids_sum_from_template']*100/$all_bids.'" aria-valuemin="0" aria-valuemax="100">
            <div class="progress-bar bg-success" style="width: '.$data['bids_sum_from_template']*100/$all_bids.'%">'.round($data['bids_sum_from_template']*100/$all_bids,1).'%</div>
          </div>';
        }
      ?>
    </div>
  </div>
  </br>
  <div class="row justify-content-center">
	  <div class="col col-12 text-center">
	    <h4>По пользователям</h4>
	  </div>
	</div>
	<div class="row justify-content-md-center">
		<div class="col col-md-12 py-3 border border-warning bg-light rounded-3">
			<table class="table table-hover table-bordered">
				<thead>
					<tr class="table-active text-center">
					<th scope="col">№</th>
					<th scope="col">ФИО</th>
          <th scope="col">Просмотр</th>
					</tr>
				</thead>
					<?php
					$query = mysqli_query($link,"SELECT id,f_name,s_name FROM users WHERE role in ('operator','admin','superadmin') and active=1 ORDER BY f_name ASC");
					$i = 1;

          $data_info_1 = array(array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"));
          $data_info_2 = array(array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"), array("None", "None"));

					while ($data = mysqli_fetch_assoc($query))
						{
              $date = date("d.m.Y",time());
              $select_date_start = strtotime($date);      
              $select_date_end = $select_date_start+86399;

              $j=0;
              while ($j<15) {
                $query_graphs_1 = mysqli_query($link, "SELECT count(id) AS bids_sum_from_date FROM bid WHERE date > ".$select_date_start ." and date < ".$select_date_end." and contractor='".$data['id']."' and status not in (5)");
                $query_graphs_2 = mysqli_query($link, "SELECT count(id) AS bids_sum_from_date FROM bid WHERE date > ".$select_date_start ." and date < ".$select_date_end." and contractor='".$data['id']."' and status in (5)");
                
                $data_graps_1 = mysqli_fetch_assoc($query_graphs_1);
                $data_graps_2 = mysqli_fetch_assoc($query_graphs_2);
                $data_info_1[$j][0] = date("d-m", $select_date_start);
                $data_info_1[$j][1] = $data_graps_1['bids_sum_from_date'];
                $data_info_2[$j][1] = $data_graps_2['bids_sum_from_date'];
                
                $select_date_start = $select_date_start - 86400;
                $select_date_end = $select_date_start + 86399;
                
                $j++;
              }
              
              $users_graps_1='
              <div style="text-align:center;"><h5>За последние 15 дней</h></div>
              </br>
              <div>
                <canvas id="bids_statistics_users_'.$i.'"></canvas>
              </div>
              <script>
                const ctx'.$i.' = document.getElementById(\'bids_statistics_users_'.$i.'\');

                new Chart(ctx'.$i.', {
                  type: \'line\',
                  data: {
                    labels: [\''.$data_info_1[14][0].'\',\''.$data_info_1[13][0].'\',\''.$data_info_1[12][0].'\',\''.$data_info_1[11][0].'\',\''.$data_info_1[10][0].'\',\''.$data_info_1[9][0].'\',\''.$data_info_1[8][0].'\',\''.$data_info_1[7][0].'\',\''.$data_info_1[6][0].'\',\''.$data_info_1[5][0].'\',\''.$data_info_1[4][0].'\',\''.$data_info_1[3][0].'\',\''.$data_info_1[2][0].'\',\''.$data_info_1[1][0].'\',\''.$data_info_1[0][0].'\'],
                    datasets: [
                      {
                        label: \'В работе\',
                        data: ['.$data_info_1[14][1].','.$data_info_1[13][1].','.$data_info_1[12][1].','.$data_info_1[11][1].','.$data_info_1[10][1].','.$data_info_1[9][1].','.$data_info_1[8][1].','.$data_info_1[7][1].','.$data_info_1[6][1].','.$data_info_1[5][1].','.$data_info_1[4][1].','.$data_info_1[3][1].','.$data_info_1[2][1].','.$data_info_1[1][1].','.$data_info_1[0][1].'],
                        borderWidth: 2,
                        borderColor: \'rgb(75, 126, 192)\',
                      },
                      {
                        label: \'Закрытые\',
                        data: ['.$data_info_2[14][1].','.$data_info_2[13][1].','.$data_info_2[12][1].','.$data_info_2[11][1].','.$data_info_2[10][1].','.$data_info_2[9][1].','.$data_info_2[8][1].','.$data_info_2[7][1].','.$data_info_2[6][1].','.$data_info_2[5][1].','.$data_info_2[4][1].','.$data_info_2[3][1].','.$data_info_2[2][1].','.$data_info_2[1][1].','.$data_info_2[0][1].'],
                        borderWidth: 2,
                        borderColor: \'rgb(110, 192, 75)\',
                      }
                    ]
                  },
                  options: {
                    scales: {
                      y: {
                        beginAtZero: true
                      }
                    }
                  }
                });
              </script>
              ';
              
              $query_2 = mysqli_query($link, "SELECT count(id) AS bids_sum_from_user FROM bid WHERE contractor='".$data['id']."' and status not in (5)");
              $data_2 = mysqli_fetch_assoc($query_2);

							$button_edit = '
                <!-- Modal user edit -->
                <div class="modal fade" id="Modal_edit_'.$i.'" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered modal-lg">
                   <div class="modal-content">
                     <div class="modal-header">
                       <h5 class="modal-title" id="exampleModalLabel">Статистика по пользователю '.$data['f_name'].' '.$data['s_name'].'</h5>
                       <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                     </div>
                     <div class="modal-body">
                      <div class="row justify-content-md-center">
                        <h4>Активных заявок: '.$data_2['bids_sum_from_user'].'</h4>
                      </div>
                      </br>
                      <div class="row justify-content-md-center">
                       '.$users_graps_1.'
                      </div>
                     </div>
                     <div class="modal-footer">
                       <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Назад</button>
                     </div>
                   </div>
                 </div>
                </div>
                <!-- Modal user edit end -->
							<div class="btn-group" role="group" aria-label="Basic mixed styles example">
             		<button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#Modal_edit_'.$i.'">Просмотр</button>
             	</div>';
							echo '
							<tr class="table-light text-center">
							 <td><center>'.$i.'</center></td>
							 <td>'.$data['f_name'].' '.$data['s_name'].'</td>
               <td>'.$button_edit.'</td>
							</tr>';
							$i++;
						}
					?>
			</table>
		</div>
	</div>
</br>

<?php
  end_load:
  require("../../data/footer.php");
?>
