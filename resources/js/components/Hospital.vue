<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header th-table">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-hospital-alt blue"></i>
                    โรงพยาบาลคู่สัญญา
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
                    <th>ชื่อโรงพยาบาล</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th v-if="$gate.isAdmin()">Action</th>  
                  </tr>
                  <tr v-for="hospital in hospitals.data" :key="hospital.id">
                    <td>{{hospital.id}}</td>
                    <td>{{hospital.name}}</td>
                    <td>{{hospital.email}}</td>
                    <td>{{hospital.tel}}</td>
                    <td v-if="$gate.isAdmin()">
                        <a href="#" @click="editModal(hospital)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteHospital(hospital.id)">
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

              <!-- เพิ่ม code pagination -->
              <div class="card-footer">
                  <pagination :data="hospitals" @pagination-change-page="getResults"></pagination>
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
                <h5 class="modal-title" v-show="editmode" id="addNewLabel">แก้ไขข้อมูลโรงพยาบาล</h5>
                <h5 class="modal-title" v-show="!editmode" id="addNewLabel">เพิ่มโรงพยาบาล</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateHospital() : createHospital()">
              <div class="modal-body">
                 <div class="form-group">
                  <input v-model="form.name" type="text" name="name"
                    placeholder="ชื่อโรงพยาบาล"
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
            hospitals : {},
            form: new Form({
              id:'',
              name: '',
              email: '',
              tel: '',
            })
          }
        },
        methods:{
            getResults(page = 1) {
              axios.get('api/hospital?page=' + page)
                .then(response => {
                  this.hospitals = response.data;
                });
            },
            updateHospital(){
              this.$Progress.start();
              //console.log('Editing data');
              this.form.put('api/hospital/'+this.form.id)
              .then(() => {
                // success
                  $('#addNew').modal('hide');
                  swal(
                            'อัพเดท!',
                            'ข้อมูลโรงพยาบาลถูกแก้ไขเรียบร้อยแล้ว',
                            'success'
                      )
                      this.$Progress.finish();
                      Fire.$emit('AfterCreate');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },
            editModal(hospital){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(hospital);
            },
            newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },
            deleteHospital(id){
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
                  this.form.delete('api/hospital/'+id).then(()=>{
                          swal(
                            'ลบ!',
                            'โรงพยาบาลถูกลบเรียบร้อยแล้ว',
                            'success'
                          )
                        Fire.$emit('AfterCreate');
                  }).catch(()=>{
                      swal("ล้มเหลว!", "มีบางอย่างผิดพลาด", "warning");
                  });
                  }
                })
            },
            loadHospitals(){
                  axios.get("api/hospital").then(({ data }) => (this.hospitals = data));
            },
            createHospital(){
              this.$Progress.start();
              
              this.form.post('api/hospital')
              .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#addNew').modal('hide')
                  toast({
                    type: 'success',
                    title: 'เพิ่มโรงพยาบาลเรียบร้อยแล้ว'
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
                axios.get('api/findHospital?q=' + query)
                .then((data) => {
                    this.hospitals = data.data
                })
                .catch(() => {
                  
                })
            })
            this.loadHospitals();
            Fire.$on('AfterCreate',() => {
              this.loadHospitals();
            })
        }
    }
</script>
