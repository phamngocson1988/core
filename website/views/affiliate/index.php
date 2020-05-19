<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js', ['position' => \yii\web\View::POS_HEAD]);
?>
<div class="container profile profile-affiliate my-5">
  <div class="row">
    <div class="col-md-3">
      <div class="card card-info text-center">
        <img class="card-img-top" src="/images//icon/mask.svg" alt="Card image">
        <div class="card-body">
          <h4 class="card-title">John Doe</h4>
          <p class="card-text">@JohnDoe93</p>
          <p class="font-weight-bold text-red">Balance: 500 KCOIN</p>
          <a href="#" class="btn btn-green" data-toggle="modal" data-target="#choosePayment">
            WITHDRAW
          </a>
        </div>
      </div>
      <!-- Modal Choose beneficiary account-->
      <div class="modal fade" id="choosePayment" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Choose beneficiary account</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <div class="d-flex mb-4">
                <button data-toggle="modal" data-target="#addBeneficiary" type="button" class="btn btn-green align-self-center mr-3">
                  <img class="icon-btn" src="/images//icon/more.svg"> Add more
                </button>
                <span class="align-self-center text-muted">(Maximum 4 accounts)</span>
              </div>

              <div class="btn-group-toggle multi-choose d-flex beneficiary" data-toggle="buttons">
                <label class="btn flex-fill w-25 mr-2 active">
                  <input type="radio" name="options" id="option1" autocomplete="off" checked="">Name <br/>Payment method
                  <div class="action">
                    <div class="edit icon-edit" data-toggle="modal" data-target="#addBeneficiary">
                      <img src="/images//icon/edit.svg"/>
                    </div>
                    <div class="del icon-del">
                      <img src="/images//icon/trash-can.svg"/>
                    </div>
                  </div>
                </label>
                <label class="btn flex-fill w-25 mr-2">
                  <input type="radio" name="options" id="option2" autocomplete="off"> Name <br/>Payment method
                  <div class="action">
                    <div class="edit icon-edit" data-toggle="modal" data-target="#addBeneficiary">
                      <img src="/images//icon/edit.svg"/>
                    </div>
                    <div class="del icon-del">
                      <img src="/images//icon/trash-can.svg"/>
                    </div>
                  </div>
                </label>
                <label class="btn flex-fill w-25 mr-2">
                  <input type="radio" name="options" id="option3" autocomplete="off"> Name <br/>Payment method
                  <div class="action">
                    <div class="edit icon-edit" data-toggle="modal" data-target="#addBeneficiary">
                      <img src="/images//icon/edit.svg"/>
                    </div>
                    <div class="del icon-del">
                      <img src="/images//icon/trash-can.svg"/>
                    </div>
                  </div>
                </label>
                <label class="btn flex-fill w-25">
                  <input type="radio" name="options" id="option4" autocomplete="off"> Name <br/>Payment method
                  <div class="action">
                    <div class="edit icon-edit" data-toggle="modal" data-target="#addBeneficiary">
                      <img src="/images//icon/edit.svg"/>
                    </div>
                    <div class="del icon-del">
                      <img src="/images//icon/trash-can.svg"/>
                    </div>
                  </div>
                </label>
              </div>
              <div class="input-group mt-4" style="max-width:300px">
                <input type="text" class="form-control" placeholder="Withdraw Amount" aria-label="Withdraw Amount" aria-describedby="button-addon2">
                <div class="input-group-append">
                  <button class="btn btn-warning text-white" type="button" id="button-addon2">Submit</button>
                </div>
              </div>
              <div class="note py-5">
                <p class="lead">Withdraw Policy</p>
                <p class="mb-0"><em>- Cut off time: 13:00 (GMT +7) Monday to Friday</em></p>
                <p class="mb-0"><em>- Payment time: 14:00 (GMT +7) Monday to Friday</em></p>
                <p class="mb-0"><em>- Transaction fee 5% of widthdraw amount</em></p>
                <p class="mb-2"><em>- Min amount = $50 (USD). Max account = $2000 (USD)</em></p>
                <p class="">Note:</p>
                <p class="mb-0"><em>The transaction are made after 13:00 (GMT + 7) on friday, will be resolve on Monday of coming week</em></p>
              </div>
              <div class="table-wrapper table-responsive bg-white table-vertical-midle">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th scope="col">No.</th>
                      <th scope="col">ID</th>
                      <th class="text-center" scope="col">Amount ($)</th>
                      <th class="text-center" scope="col">Opening</th>
                      <th class="text-center" scope="col">Ending</th>
                      <th class="text-center" scope="col">Status</th>
                      <th class="text-center" scope="col">Details</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>100$</td>
                      <th scope="row">
                        <a href="#">#12345678</a>
                        <span class="date-time">2020-03-06 20:48</span>
                      </th>
                      <td class="text-center">100$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">Pending</td>
                      <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
                    </tr>
                    <tr>
                      <td>100$</td>
                      <th scope="row">
                        <a href="#">#12345678</a>
                        <span class="date-time">2020-03-06 20:48</span>
                      </th>
                      <td class="text-center">100$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">Pending</td>
                      <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
                    </tr>
                    <tr>
                      <td>100$</td>
                      <th scope="row">
                        <a href="#">#12345678</a>
                        <span class="date-time">2020-03-06 20:48</span>
                      </th>
                      <td class="text-center">100$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">Pending</td>
                      <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
                    </tr>
                    <tr>
                      <td>100$</td>
                      <th scope="row">
                        <a href="#">#12345678</a>
                        <span class="date-time">2020-03-06 20:48</span>
                      </th>
                      <td class="text-center">100$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">Pending</td>
                      <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
                    </tr>
                    <tr>
                      <td>100$</td>
                      <th scope="row">
                        <a href="#">#12345678</a>
                        <span class="date-time">2020-03-06 20:48</span>
                      </th>
                      <td class="text-center">100$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">20$</td>
                      <td class="text-center">Pending</td>
                      <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
                    </tr>
                    <tr>
                      <th scope="row" class="text-left">GRAND TOTAL</th>
                      <td class="text-center"></td>
                      <td class="text-center"><b class="text-red">$500</b></td>
                      <td class="text-center" colspan="4"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            <!-- END Transaction History Table -->
            </div>
          </div>
        </div>
      </div>
      <!-- End Modal Choose beneficiary account-->

      <!-- MODAL ADD BENEFICIARY -->
      <div class="modal fade" id="addBeneficiary" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="">Add new beneficiary account</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body p-5">
              <h3 class="text-center">Beneficiary account info</h3>
              <form>
                <div class="form-group">
                  <label for="paymentMethod">Payment method</label>
                  <input type="email" class="form-control" id="paymentMethod" aria-describedby="emailHelp" placeholder="">
                  <!-- <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small> -->
                </div>
                <div class="form-group">
                  <label for="accountId">Account ID/No.</label>
                  <input type="number" class="form-control" id="accountId" placeholder="">
                </div>
                <div class="form-group">
                  <label for="nameHolder">Name of Holder</label>
                  <input type="text" class="form-control" id="nameHolder" placeholder="">
                </div>
                <div class="form-group">
                  <label for="region">Region</label>
                  <input type="text" class="form-control" id="region" placeholder="">
                </div>
                <button type="submit" class="btn mt-3 btn-warning text-white btn-block">SAVE</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- END MODAL ADD BENEFICIARY -->
    </div>
    <div class="col-md-9">
      <canvas id="myChart"></canvas>
      <script>
        var ctx = document.getElementById('myChart');
        var myLineChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# data1',
                data: [1, 2, 3, 4, 5, 7],
                backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
              },
              {
                label: '# data2',
                data: [5, 6, 7, 8, 9, 10],
                backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                  'rgba(255, 99, 132, 1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
              }

            ]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          },
        });
      </script>
    </div>
    <div class="col-md-12">
      <hr class="my-5" />
    </div>
    <div class="col-md-12">
      <!-- Transaction History Table -->
      <div class="d-flex bd-highlight justify-content-between align-items-center orders-history-wrapper mb-3">
        <p class="lead mb-0">Transaction history</p>
        <div class="d-flex ml-auto">
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">Start date</label>
            <input class="form-control" type="date" id="birthday" name="birthday" min="2017-04-01" max="2017-04-30">
          </div>
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">End date</label>
            <input class="form-control" type="date" id="birthday" name="birthday" min="2017-04-01" max="2017-04-30">
          </div>
          <div class="flex-fill d-flex align-items-center mr-3">
            <label class="d-block w-100 mr-2 mb-0">Status</label>
            <select class="form-control" id="status">
              <option>1</option>
              <option>2</option>
              <option>3</option>
              <option>4</option>
              <option>5</option>
            </select>
          </div>
          <div class="flex-fill">
            <a class="btn btn-primary" href="#" role="button">Filter</a>
          </div>
        </div>
      </div>

      <div class="table-wrapper table-responsive bg-white">
        <table class="table table-hover">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th class="text-center" scope="col">Amount ($)</th>
              <th class="text-center" scope="col">Commission ($)</th>
              <th class="text-center" scope="col">Status</th>
              <th class="text-center" scope="col">Details</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">
                <a href="#">#12345678</a>
                <span class="date-time">2020-03-06 20:48</span>
              </th>
              <td class="text-center">100$</td>
              <td class="text-center">20$</td>
              <td class="text-center">Pending</td>
              <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
            </tr>
            <tr>
              <th scope="row">
                <a href="#">#12345678</a>
                <span class="date-time">2020-03-06 20:48</span>
              </th>
              <td class="text-center">100$</td>
              <td class="text-center">20$</td>
              <td class="text-center">Pending</td>
              <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
            </tr>
            <tr>
              <th scope="row">
                <a href="#">#12345678</a>
                <span class="date-time">2020-03-06 20:48</span>
              </th>
              <td class="text-center">100$</td>
              <td class="text-center">20$</td>
              <td class="text-center">Pending</td>
              <td class="text-center"><span href="#" class="text-green">Comment Details</span></td>
            </tr>
            <tr>
              <th scope="row" class="text-left">GRAND TOTAL</th>
              <td class="text-center"><b class="text-red">$500</b></td>
              <td class="text-center"><b class="text-red">$100</b></td>
              <td class="text-center" colspan="2"></td>
            </tr>
          </tbody>
        </table>
      </div>
      <nav aria-label="Page navigation" class="mt-2 mb-5">
        <ul class="pagination justify-content-end">
          <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">
              <img class="icon" src="/images//icon/back.svg" />
            </a>
          </li>
          <li class="page-item"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link" href="#">
              <img class="icon" src="/images//icon/next.svg" />
            </a>
          </li>
        </ul>
      </nav>
    </div>
    <!-- END Transaction History Table -->
  </div>
</div>