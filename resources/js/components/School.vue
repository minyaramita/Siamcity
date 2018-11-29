<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header th-table">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-school blue"></i>
                    สถานศึกษา
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
                    <th>ชื่อสถานศึกษา</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>ชื่อบัญชีธนาคาร</th>
                    <th>ธนาคาร</th>
                    <th>สาขา</th>
                    <th>เลขบัญชี</th>
                    <th v-if="$gate.isAdmin()">Action</th>  
                  </tr>
                  <tr v-for="school in schools.data" :key="school.id">
                    <td>{{school.id}}</td>
                    <td>{{school.name}}</td>
                    <td>{{school.email}}</td>
                    <td>{{school.tel}}</td>
                    <td>{{school.account_name}}</td>
                    <td>{{school.bank.bank_name}}</td>
                    <td>{{school.bank_branch}}</td>
                    <td>{{school.bank_number}}</td>
                    <td v-if="$gate.isAdmin()">
                        <a href="#" @click="editModal(school)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteSchool(school.id)">
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

              <!-- เพิ่ม code pagination -->
              <div class="card-footer">
                  <pagination :data="schools" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content th-table">
              <div class="modal-header">
                <h5 class="modal-title" v-show="editmode" id="addNewLabel">แก้ไขข้อมูลสถานศึกษา</h5>
                <h5 class="modal-title" v-show="!editmode" id="addNewLabel">เพิ่มสถานศึกษา</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateSchool() : createSchool()">
              <div class="modal-body">
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name"
                    placeholder="ชื่อสถานศึกษา"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input v-model="form.email" type="email" email="email"
                      placeholder="อีเมล"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="nav-icon far fa-envelope blue"></i></span>
                    </div>
                    <has-error :form="form" field="email"></has-error>
                  </div>
                </div>
                <div class="form-group">
                  <div class="input-group">
                    <input v-model="form.tel" type="text" name="tel"
                      placeholder="เบอร์โทรศัพท์"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('tel') }">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="nav-icon fas fa-phone blue"></i></span>
                    </div>
                    <has-error :form="form" field="tel"></has-error> 
                  </div> 
                </div>     
                <div class="form-group">
                  <input v-model="form.account_name" type="text" name="account_name" id="account_name"
                    placeholder="ชื่อบัญชีธนาคาร"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('account_name') }">
                  <has-error :form="form" field="account_name"></has-error>
                </div>
                <div class="form-group">
                  <select name="bank_id" v-model="form.bank_id" id="bank_id" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_id') }">
                    <option value="">เลือกธนาคาร</option>
                    <option value="1">ธนาคารกรุงเทพ</option>
                    <option value="2">ธนาคารกสิกรไทย</option>
                    <option value="3">ธนาคารกรุงไทย</option>
                    <option value="4">ธนาคารทหารไทย</option>
                    <option value="5">ธนาคารไทยพาณิชย์</option>
                    <option value="6">ธนาคารกรุงศรีอยุธยา</option>
                    <option value="7">ธนาคารเกียรตินาคิน</option>
                    <option value="8">ธนาคารออมสิน</option>
                    <option value="9">ธนาคารธนชาต</option>
                  </select>
                  <has-error :form="form" field="bank_id"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.bank_branch" type="text" name="bank_branch" id="bank_branch"
                    placeholder="สาขา"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_branch') }">
                  <has-error :form="form" field="bank_branch"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.bank_number" type="text" name="bank_number" id="bank_number"
                    placeholder="หมายเลขบัญชี"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_number') }">
                  <has-error :form="form" field="bank_number"></has-error>
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
    </div>
</template>

<script>
    export default {
         data () {
          return {
            editmode: false,
            schools : {},
            form: new Form({
              id:'',
              name: '',
              email: '',
              tel: '',
              account_name: '',
              bank_id: '',
              bank_branch: '',
              bank_number: ''
            })
          }
        },
        methods:{
            getResults(page = 1) {
              axios.get('api/school?page=' + page)
                .then(response => {
                  this.schools = response.data;
                });
            },
            updateSchool(){
              this.$Progress.start();
              //console.log('Editing data');
              this.form.put('api/school/'+this.form.id)
              .then(() => {
                // success
                  $('#addNew').modal('hide');
                  swal(
                            'อัพเดท!',
                            'ข้อมูลสถานศึกษาถูกแก้ไขเรียบร้อยแล้ว',
                            'success'
                      )
                      this.$Progress.finish();
                      Fire.$emit('AfterCreate');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },
            editModal(school){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(school);
            },
            newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },
            deleteSchool(id){
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
                  this.form.delete('api/school/'+id).then(()=>{
                          swal(
                            'ลบ!',
                            'สถานศึกษาถูกลบเรียบร้อยแล้ว',
                            'success'
                          )
                        Fire.$emit('AfterCreate');
                  }).catch(()=>{
                      swal("ล้มเหลว!", "มีบางอย่างผิดพลาด", "warning");
                  });
                  }
                })
            },
            loadSchools(){
                  axios.get("api/school").then(({ data }) => (this.schools = data));
            },
            createSchool(){
              this.$Progress.start();
              
              this.form.post('api/school')
              .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#addNew').modal('hide')
                  toast({
                    type: 'success',
                    title: 'เพิ่มสถานศึกษาเรียบร้อยแล้ว'
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
                axios.get('api/findSchool?q=' + query)
                .then((data) => {
                    this.schools = data.data
                })
                .catch(() => {
                  
                })
            })
            this.loadSchools();
            Fire.$on('AfterCreate',() => {
              this.loadSchools();
            })
        }
    }
</script>
