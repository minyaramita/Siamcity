<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header th-table">
                <h3 class="card-title">
                  <i class="nav-icon fas fa-list-ul blue"></i>
                    การรับรายชื่อผู้ทำประกันภัย
                </h3> 
                <div class="card-tools no-print">
                  <div class="input-group input-group-sm" >
                    <!--
                    <a href="" @click.prevent="printme" target="_blank" class="btn btn-outline-primary"><i class="fa fa-print"></i> Print</a>
                    &nbsp;&nbsp;
                    -->
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
                    <th>นักเรียน</th>
                    <th>บุคลากร</th>
                    <th>วันที่รับรายชื่อ</th>
                    <th>วันคุ้มครอง</th>
                    <th>แผน</th>
                    <th>ปีการศึกษา</th>
                    <th v-if="$gate.isAdmin()" class="no-print">Action</th>  
                  </tr>
                  <tr v-for="namelist in namelists.data" :key="namelist.id">
                    <td>{{namelist.id}}</td>
                    <td>{{namelist.school.name}}</td>
                    <td>{{namelist.quantity_student}}</td>
                    <td>{{namelist.quantity_personnel}}</td>
                    <td>{{namelist.receive_date | myDate}}</td>
                    <td>{{namelist.protection_date | myDate}}</td>
                    <td>{{namelist.plan.name}}</td>
                    <td>{{namelist.year}}</td>
                    <td v-if="$gate.isAdmin()" class="no-print">
                        <a href="#" @click="editModal(namelist)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteNamelist(namelist.id)">
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

              <!-- เพิ่ม code pagination -->
              <div class="card-footer no-print">
                  <pagination :data="namelists" @pagination-change-page="getResults"></pagination>
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
                <h5 class="modal-title" v-show="editmode" id="addNewLabel">แก้ไขการรับรายชื่อ</h5>
                <h5 class="modal-title" v-show="!editmode" id="addNewLabel">เพิ่มการรับรายชื่อ</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateNamelist() : createNamelist()">
                <div class="modal-body">              
                <div class="form-group">
                    <label for="school_id">สถานศึกษา</label>
                    <select name="school_id"  class="form-control" v-model="form.school_id" 
                      :class="{ 'is-invalid': form.errors.has('school_id') }">
                      <option value="">เลือกสถานศึกษา</option>
                      <option v-for="school in schools.data" :value="school.id" :key="school.id">{{school.name}}</option>         
                    </select>
                    <has-error :form="form" field="school_id"></has-error>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="quantity_student">จำนวนนักเรียน (คน)</label>
                      <input v-model="form.quantity_student" type="text" name="quantity_student"
                        placeholder="จำนวนนักเรียน"
                        class="form-control" :class="{ 'is-invalid': form.errors.has('quantity_student') }">
                      <has-error :form="form" field="quantity_student"></has-error>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="quantity_personnel">จำนวนบุคลากร (คน)</label>
                      <input v-model="form.quantity_personnel" type="text" name="quantity_personnel"
                        placeholder="จำนวนบุคลากร"
                        class="form-control datepicker" :class="{ 'is-invalid': form.errors.has('quantity_personnel') }">
                      <has-error :form="form" field="quantity_personnel"></has-error>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="receive_date">วันที่ได้รับรายชื่อ</label>
                      <div class="input-group">
                        <input v-model="form.receive_date" type="date" name="receive_date"
                          placeholder="วันที่ได้รับรายชื่อ" class="form-control" 
                          :class="{ 'is-invalid': form.errors.has('receive_date') }">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="nav-icon far fa-calendar-alt blue"></i></span>
                        </div>
                      </div>
                      <has-error :form="form" field="receive_date"></has-error>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="protection_date">วันคุ้มครอง</label>
                      <div class="input-group">
                        <input v-model="form.protection_date" type="date" name="protection_date"
                          placeholder="วันคุ้มครอง" class="form-control" 
                          :class="{ 'is-invalid': form.errors.has('protection_date') }">
                        <div class="input-group-append">
                          <span class="input-group-text"><i class="nav-icon far fa-calendar-alt blue"></i></span>
                        </div>
                      </div>
                      <has-error :form="form" field="protection_date"></has-error>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-sm-6">
                      <label for="plan_id">แผนประกันภัย</label>
                      <select name="plan_id" v-model="form.plan_id" id="plan_id" 
                        class="form-control" :class="{ 'is-invalid': form.errors.has('plan_id') }">
                        <option value="">เลือกแผนประกันภัย</option>
                        <option value="1">60,000/6,000</option>
                        <option value="2">80,000/8,000</option>
                        <option value="3">80,000/8,000+500</option>
                        <option value="4">100,000/10,000</option>
                        <option value="5">100,000/10,000+500</option>
                        <option value="6">100,000/10,000+500+500</option>
                      </select>
                      <has-error :form="form" field="plan_id"></has-error>
                    </div>

                    <div class="form-group col-sm-6">
                      <label for="year">ปีการศึกษา</label>
                      <input v-model="form.year" type="text" name="year"
                        placeholder="ปีการศึกษา" class="form-control" :class="{ 'is-invalid': form.errors.has('year') }">
                      <has-error :form="form" field="year"></has-error>
                    </div> 
                </div>

                <div class="form-group">
                  <label for="detail">รายละเอียด</label>
                  <textarea v-model="form.detail" name="detail" id="detail"
                    placeholder="รายละเอียด"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea>
                  <has-error :form="form" field="detail"></has-error>          
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
            namelists : {},
            form: new Form({
              id:'',
              school_id: '',
              quantity_student: '',
              quantity_personnel: '',
              receive_date: '',
              protection_date: '',
              plan_id: '',
              year: '',
              detail: ''
            })
          }
        },
        methods:{
            printme() {
              window.print();
            },
            getResults(page = 1) {
              axios.get('api/namelist?page=' + page)
                .then(response => {
                  this.namelists = response.data;
                });
            },
            updateNamelist(){
              this.$Progress.start();
              //console.log('Editing data');
              this.form.put('api/namelist/'+this.form.id)
              .then(() => {
                // success
                  $('#addNew').modal('hide');
                  swal(
                            'อัพเดท!',
                            'ข้อมูลการรับรายชื่อผู้ทำประกันภัยถูกแก้ไขเรียบร้อยแล้ว',
                            'success'
                      )
                      this.$Progress.finish();
                      Fire.$emit('AfterCreate');
              })
              .catch(() => {
                this.$Progress.fail();
              });
            },
            editModal(namelist){
              this.editmode = true;
              this.form.reset();
              $('#addNew').modal('show');
              this.form.fill(namelist);
            },
            newModal(){
              this.editmode = false;
              this.form.reset();
              $('#addNew').modal('show');
            },
            deleteNamelist(id){
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
                  this.form.delete('api/namelist/'+id).then(()=>{
                          swal(
                            'ลบ!',
                            'การรับรายชื่อผู้ทำประกันภัยถูกลบเรียบร้อยแล้ว',
                            'success'
                          )
                        Fire.$emit('AfterCreate');
                  }).catch(()=>{
                      swal("ล้มเหลว!", "มีบางอย่างผิดพลาด", "warning");
                  });
                  }
                })
            },
            loadNamelists(){
                  axios.get("api/namelist").then(({ data }) => (this.namelists = data));
            },
            loadSchools(){
                  axios.get("api/school").then(({ data }) => (this.schools = data));
            },
            
            createNamelist(){
              this.$Progress.start();
              
              this.form.post('api/namelist')
              .then(()=>{
                  Fire.$emit('AfterCreate');
                  $('#addNew').modal('hide')
                  toast({
                    type: 'success',
                    title: 'เพิ่มการรับรายชื่อผู้ทำประกันภัยเรียบร้อยแล้ว'
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
                axios.get('api/findNamelist?q=' + query)
                .then((data) => {
                    this.namelists = data.data
                })
                .catch(() => {
                  
                })
            })
            this.loadNamelists();
            this.loadSchools();
            Fire.$on('AfterCreate',() => {
              this.loadNamelists();
            })
        }
    }
   
</script>
