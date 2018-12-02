<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header th-table">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-users blue"></i>
                    ผู้ทำประกันภัย
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
                    <th>ชื่อ-นามสกุล</th>
                    <th>ชั้นเรียน</th>
                    <th>โรงเรียน</th>
                    <th>แผน</th>
                    <th>วันคุ้มครอง</th>
                    <th>ปีการศึกษา</th>
                    <th>Action</th>  
                  </tr>
                  <tr v-for="insurer in insurers.data" :key="insurer.id">
                    <td>{{insurer.id}}</td>
                    <td>{{insurer.title.name}}{{insurer.ins_fname}}  {{insurer.ins_lname}}</td>
                    <td>{{insurer.ins_class}}</td>
                    <td>{{insurer.namelist.school.name}}</td>
                    <td>{{insurer.namelist.plan.name}}</td>
                    <td>{{insurer.namelist.protection_date | myDate}}</td>
                    <td>{{insurer.namelist.year}}</td>
                    <td v-if="$gate.isUser()">
                        <a href="#" @click="viewModal(insurer)">
                            <i class="far fa-eye cyan"></i>
                        </a>
                    </td>
                    <td v-if="$gate.isAdmin()">
                        <a href="#" @click="viewModal(insurer)">
                            <i class="far fa-eye cyan"></i>
                        </a>
                        /
                        <a href="#" @click="editModal(insurer)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteInsurer(insurer.id)">
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

              <!-- เพิ่ม code pagination -->
              <div class="card-footer">
                  <pagination :data="insurers" @pagination-change-page="getResults"></pagination>
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
                <h5 class="modal-title" v-show="editmode" id="addNewLabel">แก้ไขข้อมูลผู้ทำประกันภัย</h5>
                <h5 class="modal-title" v-show="!editmode" id="addNewLabel">เพิ่มผู้ทำประกันภัย</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateInsurer() : createInsurer()">
              <div class="modal-body">
                <div class="form-group">
                    <label for="namelist_id">รหัสการรับรายชื่อ</label>
                    <select name="namelist_id"  class="form-control" v-model="form.namelist_id" 
                      :class="{ 'is-invalid': form.errors.has('namelist_id') }">
                      <option value="">เลือกรหัสการรับรายชื่อ</option>
                      <option v-for="namelist in namelists.data" :value="namelist.id" :key="namelist.id">รหัส: {{namelist.id}} | {{namelist.school.name}} ปีการศึกษา {{namelist.year}}</option>         
                    </select>
                    <has-error :form="form" field="namelist_id"></has-error>
                </div>
                
                <div class="form-group">
                  <label for="title_id">คำนำหน้าชื่อ</label>
                  <select name="title_id" v-model="form.title_id" id="title_id" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('title_id') }">
                    <option value="">เลือกคำนำหน้าชื่อ</option>
                    <option value="1">เด็กชาย</option>
                    <option value="2">เด็กหญิง</option>
                    <option value="3">นาย</option>
                    <option value="4">นางสาว</option>
                    <option value="5">นาง</option>
                    <option value="6">ว่าที่ร้อยตรี</option>
                  </select>
                  <has-error :form="form" field="title_id"></has-error>
                </div>
                <div class="row"> 
                  <div class="form-group col-sm-6">
                    <label for="ins_fname">ชื่อจริง</label>
                    <input v-model="form.ins_fname" type="text" name="ins_fname"
                      placeholder="ชื่อจริง"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('ins_fname') }">
                    <has-error :form="form" field="ins_fname"></has-error>
                  </div>
                  <div class="form-group col-sm-6">
                    <label for="ins_lname">นามสกุล</label>
                    <input v-model="form.ins_lname" type="text" name="ins_lname"
                      placeholder="นามสกุล"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('ins_lname') }">
                    <has-error :form="form" field="ins_lname"></has-error>
                  </div>
                </div>
                <div class="form-group">
                  <label for="ins_class">ชั้นเรียน</label>
                  <input v-model="form.ins_class" type="text" name="ins_class"
                    placeholder="ชั้นเรียน"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ins_class') }">
                  <has-error :form="form" field="ins_class"></has-error>
                </div>
                <div class="form-group">
                  <label for="ins_type">ประเภท </label>
                    <div class="form-check-inline">
                      <label class="form-check-label" for="radio1">
                        <input v-model="form.ins_type" id="radio1" type="radio" class="form-check-input" :class="{ 'is-invalid': form.errors.has('ins_type') }" name="ins_type" value="นักเรียน" checked>นักเรียน/นักศึกษา
                      </label>
                    </div>
                    <div class="form-check-inline">
                      <label class="form-check-label" for="radio2">
                        <input v-model="form.ins_type" id="radio2" type="radio" class="form-check-input" :class="{ 'is-invalid': form.errors.has('ins_type') }" name="ins_type" value="บุคลากร">บุคลากร
                      </label>
                    </div>
                    <has-error :form="form" field="ins_type"></has-error>
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
          <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content th-table">
              <div class="modal-header th-table">
                <h4 class="modal-title"> <i class="nav-icon fas fa-users blue"></i>  ข้อมูลผู้ทำประกันภัย</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body th-table">
                <div class="row">
                  <div class="col-sm-6">
                    <b>วันเริ่มคุ้มครอง : </b>{{this.form.namelist.protection_date | myDate}}<br>
                    <b>แผน : </b> {{this.form.namelist.plan.name}}<br>
                    <b>ปีการศึกษา : </b> {{this.form.namelist.year}}<br><br>
                    
                    
                  </div> <!-- /.col -->
                  <div class="col-sm-6">
                    <b>รหัสการรับรายชื่อ :  </b>{{this.form.namelist.id}}<br>
                    <b>รหัสผู้ทำประกัน :  </b>{{this.form.id}}<br>
                  </div> <!-- /.col -->     
                </div> <!-- /.row -->

                <div class="row">
                <div class="col-12 table-responsive">
                  <table class="table">
                    <thead>
                    <tr>
                      <th>โรงเรียน</th>
                      <th>ชื่อ-นามสกุล</th>
                      <th>ชั้นเรียน</th>
                      <th>ประเภท</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>{{this.form.namelist.school.name}}</td>
                      <td>{{this.form.title.name}}{{this.form.ins_fname}} {{this.form.ins_lname}}</td>
                      <td>{{this.form.ins_class}}</td>
                      <td>{{this.form.ins_type}}</td>
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
            namelists : {},
            claims : {},
            insurers : {},
            form: new Form({
                  id:'',
                  title_id: '',
                  ins_fname: '',
                  ins_lname: '',
                  ins_class: '',
                  ins_type: '',
                  namelist_id: '',
                  title: {
                      name: '',
                  },
                  namelist: {
                      id: '',
                      protection_date: '',
                      year: '',
                      plan: {
                        name: '',
                      },
                      school: {
                        name: '',
                      },
                  }
            })
          }
        },
        methods:{
            viewModal(insurer){
              $('#viewModal').modal('show');
              this.form.fill(insurer);                
            },
            getResults(page = 1) {
              axios.get('api/insurer?page=' + page)
                .then(response => {
                  this.insurers = response.data;
                });
            },
            updateInsurer(){
              this.$Progress.start();
              //console.log('Editing data');
              this.form.put('api/insurer/'+this.form.id)
              .then(() => {
                // success
                  $('#addNew').modal('hide');
                  swal(
                            'อัพเดท!',
                            'ข้อมูลผู้ทำประกันภัยถูกแก้ไขเรียบร้อยแล้ว',
                            'success'
                      )
                      this.$Progress.finish();
                      Fire.$emit('AfterCreate');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },
            editModal(insurer){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(insurer);
            },
            newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },
            deleteInsurer(id){
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
                  this.form.delete('api/insurer/'+id).then(()=>{
                          swal(
                            'ลบ!',
                            'ผู้ทำประกันภัยถูกลบเรียบร้อยแล้ว',
                            'success'
                          )
                        Fire.$emit('AfterCreate');
                  }).catch(()=>{
                      swal("ล้มเหลว!", "มีบางอย่างผิดพลาด", "warning");
                  });
                  }
                })
            },
            loadInsurers(){
                  axios.get("api/insurer").then(({ data }) => (this.insurers = data));
            },
            loadNamelists(){
                  axios.get("api/namelist").then(({ data }) => (this.namelists = data));
            },
            createInsurer(){
              this.$Progress.start();
              
              this.form.post('api/insurer')
              .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#addNew').modal('hide')
                  toast({
                    type: 'success',
                    title: 'เพิ่มผู้ทำประกันภัยเรียบร้อยแล้ว'
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
                axios.get('api/findInsurer?q=' + query)
                .then((data) => {
                    this.insurers = data.data
                })
                .catch(() => {
                  
                })
            })
            this. loadNamelists();
            this.loadInsurers();
            Fire.$on('AfterCreate',() => {
              this.loadInsurers();
            })
        }
    }
</script>
