<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header th-table">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-user-injured blue"></i>
                    การเคลมประกัน
                </h3> 
                <div class="card-tools">
                  <div class="input-group input-group-sm" >
                    <button type="button" class="btn btn-primary" @click="newModal()" v-if="$gate.isAdmin()">
                        เพิ่ม
                        <i class="fas fa-plus-square"></i>
                    </button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover th-table">
                  <tbody><tr>
                    <th>รหัส</th>
                    <th>สถานศึกษา</th>
                    <th>ชื่อ-นามสกุล</th>
                    <th>สาเหตุ</th>
                    <th>ตั้งเบิก</th>
                    <th>ปีการศึกษา</th>
                    <th>สถานะ</th>  
                    <th>Action</th>  
                  </tr>
                  <tr v-for="claim in claims.data" :key="claim.id">
                    <td>{{claim.id}}</td>
                    <td>{{claim.insurer.namelist.school.name}}</td>
                    <td>{{claim.insurer.title.name}}{{claim.insurer.ins_fname}}  {{claim.insurer.ins_lname}}</td>
                    <td>{{claim.accident_cause}}</td>
                    <td>{{claim.withdraw_amount | numFormat('0,0.00')}}</td>
                    <td>{{claim.insurer.namelist.year}}</td>
                    <td v-if="claim.status_id === 1">
                      <span class="label label-mali" style="color: #fff;">{{claim.status.name}}</span>
                    </td>
                    <td v-if="claim.status_id === 2">
                      <span class="label label-info">{{claim.status.name}}</span>
                    </td>
                    <td v-if="claim.status_id === 3">
                      <span class="label label-success">{{claim.status.name}}</span>
                    </td>
                    <td v-if="claim.status_id === 4">
                      <span class="label label-danger">{{claim.status.name}}</span>
                    </td>

                    <td v-if="$gate.isUser()">
                        <a href="#" @click="viewModal(claim)">
                            <i class="far fa-eye cyan"></i>
                        </a>
                    </td>

                    <td v-if="$gate.isAdmin()">
                        <a href="#" @click="viewModal(claim)">
                            <i class="far fa-eye cyan"></i>
                        </a>
                        /
                        <a href="#" @click="editModal(claim)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteClaim(claim.id)">
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>

                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

              <!-- เพิ่ม code pagination -->
              <div class="card-footer">
                  <pagination :data="claims" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content th-table">
              <div class="modal-header">
                <h5 class="modal-title" v-show="editmode" id="addNewLabel">แก้ไขข้อมูลการเคลมประกัน</h5>
                <h5 class="modal-title" v-show="!editmode" id="addNewLabel">เพิ่มการเคลมประกัน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateClaim() : createClaim()">
              <div class="modal-body">
                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="claim_date">วันที่ส่งเอกสารเคลม</label>
                      <div class="input-group">
                        <input v-model="form.claim_date" type="text" name="claim_date"
                          placeholder="วันที่ส่งเอกสารเคลม" class="datepicker form-control" 
                          :class="{ 'is-invalid': form.errors.has('claim_date') }">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="nav-icon far fa-calendar-alt blue"></i></span>
                        </div>
                      </div>
                      <has-error :form="form" field="claim_date"></has-error>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="ins_id">รหัสผู้ทำประกันภัย</label>
                      <input v-model="form.ins_id" type="text" name="ins_id"
                        placeholder="รหัสผู้ทำประกันภัย"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('ins_id') }">
                      <has-error :form="form" field="ins_id"></has-error>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="accident_date">วันที่เกิดอุบัติเหตุ</label>
                      <div class="input-group">
                        <input v-model="form.accident_date" type="text" name="accident_date"
                          placeholder="วันที่เกิดอุบัติเหตุ" class="form-control datepicker" 
                          :class="{ 'is-invalid': form.errors.has('accident_date') }">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="nav-icon far fa-calendar-alt blue"></i></span>
                        </div>
                      </div>
                      <has-error :form="form" field="accident_date"></has-error>
                    </div>
                    
                    <div class="form-group col-sm-6">
                      <label for="accident_cause">สาเหตุอุบัติเหตุ</label>
                      <input v-model="form.accident_cause" type="text" name="accident_cause"
                        placeholder="สาเหตุอุบัติเหตุ"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('accident_cause') }">
                      <has-error :form="form" field="accident_cause"></has-error>
                    </div>
                </div>
                
                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="withdraw_amount">ยอดเงินที่ตั้งเบิก (บาท)</label>
                      <input v-model="form.withdraw_amount" type="text" name="withdraw_amount"
                        placeholder="ยอดเงินที่ตั้งเบิก"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('withdraw_amount') }">
                      <has-error :form="form" field="withdraw_amount"></has-error>
                    </div>
                    
                    <div class="form-group col-sm-6">
                      <label for="approve_amount">ยอดเงินที่อนุมัติ (บาท)</label>
                      <input v-model="form.approve_amount" type="text" name="approve_amount"
                        placeholder="ยอดเงินที่อนุมัติ"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('approve_amount') }">
                      <has-error :form="form" field="approve_amount"></has-error>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="pay_date">วันที่จ่ายเงินเคลม</label>
                      <div class="input-group">
                        <input v-model="form.pay_date" type="text" name="pay_date"
                          placeholder="วันที่จ่ายเงินเคลม" class="form-control datepicker" 
                          :class="{ 'is-invalid': form.errors.has('pay_date') }">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="nav-icon far fa-calendar-alt blue"></i></span>
                        </div>
                      </div>
                      <has-error :form="form" field="pay_date"></has-error>
                    </div>
                    
                    <div class="form-group col-sm-6">
                      <label for="payType">รูปแบบการจ่ายเงินเคลม</label>
                      <select name="payType" v-model="form.payType" id="payType" 
                        class="form-control" :class="{ 'is-invalid': form.errors.has('payType') }">
                        <option value="">เลือกรูปแบบการจ่ายเงิน</option>
                        <option value="เงินสด">เงินสด</option>
                        <option value="โอนเงินเข้าบัญชีธนาคาร">โอนเงินเข้าบัญชีธนาคาร</option>
                        <option value="ดราฟท์">ดราฟท์</option>
                      </select>
                      <has-error :form="form" field="payType"></has-error>
                    </div>
                </div>

                <div class="form-group">
                  <label for="detail">รายละเอียด</label>
                  <textarea v-model="form.detail" name="detail" id="detail"
                    placeholder="รายละเอียด"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea>
                  <has-error :form="form" field="detail"></has-error>          
                </div>
                
                <div class="form-group">
                  <label for="status_id">สถานะ</label>
                  <select name="status_id" v-model="form.status_id" id="status_id" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('status_id') }">
                    <option value="">เลือกสถานะ</option>
                    <option value="1">รอตรวจสอบเอกสาร</option>
                    <option value="2">รอการพิจารณาอนุมัติเคลม</option>
                    <option value="3">อนุมัติจ่ายเคลม</option>
                    <option value="4">ไม่อนุมัติจ่ายเคลม</option>
                  </select>
                  <has-error :form="form" field="status_id"></has-error>
                </div>            
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                <button v-show="editmode" type="submit" class="btn btn-primary">อัพเดท</button>
                <button v-show="!editmode" type="submit" class="btn btn-primary">บันทึก</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        <!-- ./Edit Modal -->
        
        <!-- View Modal -->
        <div class="modal fade" id="viewModal">
          <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content th-table">
              <div class="modal-header th-table">
                <h4 class="modal-title"> <i class="nav-icon fas fa-user-injured blue"></i>  ข้อมูลการเคลมประกัน</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body th-table">
                <div class="row">
                  <div class="col-sm-4">
                    <b>วันเริ่มคุ้มครอง : </b>{{this.form.insurer.namelist.protection_date | myDate}}<br>
                    <b>แผน : </b> {{this.form.insurer.namelist.plan.name}}<br>
                    <b>ปีการศึกษา : </b> {{this.form.insurer.namelist.year}}<br>
                  </div> <!-- /.col -->
                  <div class="col-sm-4">
                    <b>รหัสเคลม :  </b>{{this.form.id}}<br>
                    <b>รหัสผู้ทำประกัน :  </b>{{this.form.ins_id}}<br>
                    <b>วันที่เคลม :  </b> {{this.form.claim_date | myDate}}<br>  
                  </div> <!-- /.col -->
                  <div class="col-sm-4">
                    <b>โรงเรียน : </b> {{this.form.insurer.namelist.school.name}}<br>
                    <b>ชื่อ-นามสกุล : </b> {{this.form.insurer.title.name}}{{this.form.insurer.ins_fname}} {{this.form.insurer.ins_lname}}<br>
                    <b>ชั้นเรียน : </b> {{this.form.insurer.ins_class}}<br>
                    <b>ประเภท : </b> {{this.form.insurer.ins_type}}<br><br>
                  </div> <!-- /.col -->     
                </div> <!-- /.row -->

                <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table">
                    <thead>
                    <tr>
                      <th>วันที่เกิดอุบัติเหตุ</th>
                      <th>สาเหตุอุบัติเหตุ</th>
                      <th>ตั้งเบิก</th>
                      <th>อนุมัติ</th>
                      <th>สถานะ</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>{{this.form.accident_date | myDate}}</td>
                      <td>{{this.form.accident_cause}}</td>
                      <td>{{this.form.withdraw_amount | numFormat('0,0.00')}} บาท</td>
                      <td style="color:red;"><b>{{this.form.approve_amount | numFormat('0,0.00')}} </b> บาท</td>
                      <td v-if="form.status_id === 1"> 
                        <span class="label label-mali" style="color: #fff;">{{this.form.status.name}}</span>
                      </td>
                      <td v-if="form.status_id === 2"> 
                        <span class="label label-info" style="color: #fff;">{{this.form.status.name}}</span>
                      </td>
                      <td v-if="form.status_id === 3"> 
                        <span class="label label-success" style="color: #fff;">{{this.form.status.name}}</span>
                      </td>
                      <td v-if="form.status_id === 4"> 
                        <span class="label label-danger" style="color: #fff;">{{this.form.status.name}}</span>
                      </td>
                    </tr>
                    <tr >
                      <td colspan="5"><b>รายละเอียด : </b>{{this.form.detail}}</td>
                    </tr>
                    <tr v-if="form.status_id === 3">
                      <td colspan="5"><b>รูปแบบการจ่ายเงิน : </b>{{this.form.payType}}</td>
                    </tr>
                    <tr v-if="form.status_id === 3">
                      <td colspan="5"><b>วันที่จ่ายเงิน : </b>{{this.form.pay_date | myDate}}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
                <!-- /.col -->
              </div>
                
              </div> <!-- /.modal-body -->

              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
              </div>
            </div>
          </div>
        </div>
        <!-- ./View Modal -->
    </div>
</template>

<script>
    export default {
         data () {
          return {
            editmode: false,
            claims : {},
            form: new Form({
              id:'',
              ins_id: '',
              claim_date: '',
              accident_cause: '',
              accident_date: '',
              withdraw_amount: '',
              approve_amount: '',
              pay_date: '',
              payType: '',
              detail: '',
              status_id: '',
              status: {
                name: '',
              },
              insurer: {
                id: '',
                ins_fname: '',
                ins_lname: '',
                ins_class: '',
                ins_type: '',
                title: {
                  name: '',
                },
                namelist: {
                  protection_date: '',
                  year: '',
                  plan: {
                    name: '',
                  },
                  school: {
                    name: '',
                  },
                },
              },                
            })
          }
        },
        methods:{
            viewModal(claim){
              $('#viewModal').modal('show');
              this.form.fill(claim);
            },
            getResults(page = 1) {
              axios.get('api/claim?page=' + page)
                .then(response => {
                  this.claims = response.data;
                });
            },
            updateClaim(){
              this.$Progress.start();
              //console.log('Editing data');
              this.form.put('api/claim/'+this.form.id)
              .then(() => {
                // success
                  $('#addNew').modal('hide');
                  swal(
                            'อัพเดท!',
                            'ข้อมูลการเคลมประกันถูกแก้ไขเรียบร้อยแล้ว',
                            'success'
                      )
                      this.$Progress.finish();
                      Fire.$emit('AfterCreate');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },
            editModal(claim){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(claim);
            },
            newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },
            deleteClaim(id){
              swal({
                  title: 'คุณแน่ใจหรือไม่ ที่ต้องการลบ ?',
                  text: "คุณจะไม่สามารถย้อนกลับได้ !",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'ใช่, ต้องการลบ!'
                }).then((result) => {
                  //send request to the server
                  if (result.value) {
                  this.form.delete('api/claim/'+id).then(()=>{
                          swal(
                            'ลบ!',
                            'การเคลมประกันถูกลบเรียบร้อยแล้ว',
                            'success'
                          )
                        Fire.$emit('AfterCreate');
                  }).catch(()=>{
                      swal("ล้มเหลว!", "มีบางอย่างผิดพลาด", "warning");
                  });
                  }
                })
            },
            loadClaims(){
                  axios.get("api/claim").then(({ data }) => (this.claims = data));
            },
            createClaim(){
              this.$Progress.start();
              
              this.form.post('api/claim')
              .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#addNew').modal('hide')
                  toast({
                    type: 'success',
                    title: 'เพิ่มการเคลมประกันเรียบร้อยแล้ว'
                  })
                  this.$Progress.finish();
              })
              .catch(()=>{

              })
            }
        },
        created() {
            Fire.$on('searching',() => {
                let query = this.$parent.search;
                axios.get('api/findClaim?q=' + query)
                .then((data) => {
                    this.claims = data.data
                })
                .catch(() => {
                  
                })
            })
            this.loadClaims();
            Fire.$on('AfterCreate',() => {
              this.loadClaims();
            })
        }
    }
</script>
