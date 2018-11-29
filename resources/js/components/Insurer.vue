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
                    <th>คำนำหน้าชื่อ</th>
                    <th>ชื่อจริง</th>
                    <th>นามสกุล</th>
                    <th>ชั้นเรียน</th>
                    <th>โรงเรียน</th>
                    <th>ประเภท</th>
                    <th>รหัสการรับรายชื่อ</th>
                    <th v-if="$gate.isAdmin()">Action</th>  
                  </tr>
                  <tr v-for="insurer in insurers.data" :key="insurer.id">
                    <td>{{insurer.id}}</td>
                    <td>{{insurer.title.name}}</td>
                    <td>{{insurer.ins_fname}}</td>
                    <td>{{insurer.ins_lname}}</td>
                    <td>{{insurer.ins_class}}</td>
                    <td>{{insurer.namelist.school_id}}</td>
                    <td>{{insurer.ins_type}}</td>
                    <td>{{insurer.namelist_id}}</td>
                    <td v-if="$gate.isAdmin()">
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

        <!-- Modal -->
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
                <div class="form-group">
                  <input v-model="form.ins_fname" type="text" name="ins_fname"
                    placeholder="ชื่อจริง"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ins_fname') }">
                  <has-error :form="form" field="ins_fname"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.ins_lname" type="text" name="ins_lname"
                    placeholder="นามสกุล"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ins_lname') }">
                  <has-error :form="form" field="ins_lname"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.ins_class" type="text" name="ins_class"
                    placeholder="ชั้นเรียน"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('ins_class') }">
                  <has-error :form="form" field="ins_class"></has-error>
                </div>
                <div class="form-group">
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
                <div class="form-group">
                  <input v-model="form.namelist_id" type="text" name="namelist_id" id="namelist_id"
                    placeholder="รหัสการรับรายชื่อ"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('namelist_id') }">
                  <has-error :form="form" field="namelist_id"></has-error>
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
            insurers : {},
            form: new Form({
              id:'',
              title_id: '',
              ins_fname: '',
              ins_lname: '',
              ins_class: '',
              ins_type: '',
              namelist_id: ''
            })
          }
        },
        methods:{
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
            this.loadInsurers();
            Fire.$on('AfterCreate',() => {
              this.loadInsurers();
            })
        }
    }
</script>
