<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">สถานศึกษา</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 600px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                    <div class="input-group-append">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addNew">
                        เพิ่ม
                        <i class="fas fa-plus-square"></i>
                    </button>
                  </div>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody><tr>
                    <th>รหัส</th>
                    <th>ชื่อสถานศึกษา</th>
                    <th>อีเมล</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>ชื่อบัญชีธนาคาร</th>
                    <th>ธนาคาร</th>
                    <th>สาขา</th>
                    <th>เลขบัญชี</th>
                    <th>แก้ไข</th>
                  </tr>
                  <tr v-for="school in schools.data" :key="school.id">
                    <td>{{school.id}}</td>
                    <td>{{school.name}}</td>
                    <td>{{school.email}}</td>
                    <td>{{school.tel}}</td>
                    <td>{{school.account_name}}</td>
                    <td>{{school.bank_id}}</td>
                    <td>{{school.bank_branch}}</td>
                    <td>{{school.bank_number}}</td>
                    <td>
                        <a href="#" >
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" >
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addNewLabel">เพิ่มสถานศึกษา</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="createSchool()">
              <div class="modal-body">
                
                 <div class="form-group">
                  <input v-model="form.name" type="text" name="name"
                    placeholder="ชื่อสถานศึกษา"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.email" type="email" email="email"
                    placeholder="อีเมล"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                  <has-error :form="form" field="email"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.tel" type="text" name="tel"
                    placeholder="เบอร์โทรศัพท์"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('tel') }">
                  <has-error :form="form" field="tel"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.account_name" type="account_name" name="account_name" id="account_name"
                    placeholder="ชื่อบัญชีธนาคาร"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('account_name') }">
                  <has-error :form="form" field="account_name"></has-error>
                </div>
                <div class="form-group">
                  <select name="bank_id" v-model="form.bank_id" id="bank_id" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_id') }">
                    <option value="">เลือกธนาคาร</option>
                    <option value="ธนาคารกรุงเทพ">ธนาคารกรุงเทพ</option>
                    <option value="ธนาคารกสิกรไทย">ธนาคารกสิกรไทย</option>
                    <option value="ธนาคารกรุงไทย">ธนาคารกรุงไทย</option>
                    <option value="ธนาคารทหารไทย">ธนาคารทหารไทย</option>
                    <option value="ธนาคารไทยพาณิชย์">ธนาคารไทยพาณิชย์</option>
                    <option value="ธนาคารกรุงศรีอยุธยา">ธนาคารกรุงศรีอยุธยา</option>
                    <option value="ธนาคารเกียรตินาคิน">ธนาคารเกียรตินาคิน</option>
                    <option value="ธนาคารออมสิน">ธนาคารออมสิน</option>
                    <option value="ธนาคารธนชาต">ธนาคารธนชาต</option>
                  </select>
                  <has-error :form="form" field="bank_id"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.bank_branch" type="bank_branch" name="bank_branch" id="bank_branch"
                    placeholder="สาขา"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_branch') }">
                  <has-error :form="form" field="bank_branch"></has-error>
                </div>
                <div class="form-group">
                  <input v-model="form.bank_number" type="bank_number" name="bank_number" id="bank_number"
                    placeholder="หมายเลขบัญชี"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('bank_number') }">
                  <has-error :form="form" field="bank_number"></has-error>
                </div>               

              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary">บันทึก</button>
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
            loadSchools(){
                  axios.get("api/school").then(({ data }) => (this.schools = data));
            },
            createSchool(){
              this.form.post('api/school')
          
              
            }
        },
        created() {
            this.loadSchools();
        }
    }
</script>
