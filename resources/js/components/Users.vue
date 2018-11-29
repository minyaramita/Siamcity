<template>
    <div class="container">
        <div class="row mt-3" v-if="$gate.isAdmin()">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header th-table">
                <h3 class="card-title"><i class="nav-icon fas fa-users-cog blue"></i>
                  ผู้ใช้งาน
                </h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-primary" @click="newModal()">
                    เพิ่ม
                    <i class="fas fa-user-plus fa-fw"></i>
                  </button>
                </div>
              </div>

              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover th-table">
                  <tbody>
                    <tr>
                      <th>รหัส</th>
                      <th>ชื่อ-นามสกุล</th>
                      <th>อีเมล</th>
                      <th>เบอร์โทรศัพท์</th>
                      <th>สถานะ</th>
                      <th>วันที่เพิ่ม</th>
                      <th>Action</th>
                  </tr>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>{{user.id}}</td>
                    <td>{{user.name}}</td>
                    <td>{{user.email}}</td>
                    <td>{{user.tel}}</td>
                    <td>{{user.type | upText}}</td>
                    <td>{{user.created_at | myDate}}</td>
                    <td>
                        <a href="#" @click="editModal(user)">
                            <i class="fa fa-edit blue"></i>
                        </a>
                        /
                        <a href="#" @click="deleteUser(user.id)">
                            <i class="fa fa-trash red" ></i>
                        </a>
                    </td>
                  </tr>
                </tbody></table>
              </div>
              <!-- /.card-body -->

              <!-- เพิ่ม code pagination -->
              <div class="card-footer">
                  <pagination :data="users" @pagination-change-page="getResults"></pagination>
              </div>

            </div>
            <!-- /.card -->
          </div>
        </div>

        <!-- ให้แสดงหน้า 404 ถ้าสถานะ ไม่ใช่ผู้ดูแลระบบ-->
        <div v-if="!$gate.isAdmin()">
          <not-found></not-found>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content th-table">
              <div class="modal-header">
                <h5 class="modal-title" v-show="editmode" id="addNewLabel"><i class="fas fa-user-plus fa-fw blue"></i> แก้ไขข้อมูลผู้ใช้งาน</h5>
                <h5 class="modal-title" v-show="!editmode" id="addNewLabel"><i class="fas fa-user-plus fa-fw blue"></i> เพิ่มผู้ใช้งาน</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>

              <form @submit.prevent="editmode ? updateUser() : createUser()">
              <div class="modal-body">
            
                <div class="form-group">
                  <input v-model="form.name" type="text" name="name"
                    placeholder="ชื่อ-นามสกุล"
                    class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                  <has-error :form="form" field="name"></has-error>
                </div>
                <div class="form-group">
                  <select name="type" v-model="form.type" id="type" 
                    class="form-control" :class="{ 'is-invalid': form.errors.has('type') }">
                    <option value="">เลือกสถานะ</option>
                    <option value="ผู้ดูแลระบบ">ผู้ดูแลระบบ</option>
                    <option value="ผู้ใช้งาน">ผู้ใช้งาน</option>
                  </select>
                  <has-error :form="form" field="type"></has-error>
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
                    <input v-model="form.password" type="password" name="password" id="password"
                      placeholder="รหัสผ่าน"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                    <div class="input-group-append">
                        <span class="input-group-text"><i class="nav-icon fas fa-unlock-alt blue"></i></span>
                    </div>
                    <has-error :form="form" field="password"></has-error>
                  </div>
                  <label v-show="editmode" for="password" class="col-sm-8 control-label">
                    <p style="font-size: 12px; font-weight: lighter; color: Gray;">เว้นว่างไว้ หากไม่ต้องการเปลี่ยนรหัสผ่าน</p>
                  </label>
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
            users : {},
            form: new Form({
              id:'',
              name: '',
              email: '',
              password: '',
              tel: '',
              photo: '',
              type: ''
            })
          }
        },
        methods:{
          getResults(page = 1) {
            axios.get('api/user?page=' + page)
              .then(response => {
                this.users = response.data;
              });
          },
          updateUser(){
            this.$Progress.start();
            //console.log('Editing data');
            this.form.put('api/user/'+this.form.id)
            .then(() => {
              // success
                $('#addNew').modal('hide');
                swal(
                          'อัพเดท!',
                          'ข้อมูลผู้ใช้งานถูกแก้ไขเรียบร้อยแล้ว',
                          'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
            })
            .catch(() => {
              this.$Progress.fail();
            });
          },
          editModal(user){
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(user);
          },
          newModal(){
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
          },
          deleteUser(id){
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
                this.form.delete('api/user/'+id).then(()=>{
                        swal(
                          'ลบ!',
                          'ผู้ใช้งานถูกลบเรียบร้อยแล้ว',
                          'success'
                        )
                      Fire.$emit('AfterCreate');
                }).catch(()=>{
                    swal("ล้มเหลว!", "มีบางอย่างผิดพลาด", "warning");
                });
                }
              })
          },
          loadUsers(){
            if(this.$gate.isAdmin()){
              axios.get("api/user").then(({ data }) => (this.users = data));
            }
          },
          createUser(){
            this.$Progress.start();

            this.form.post('api/user')
            .then(()=>{
              Fire.$emit('AfterCreate');
              $('#addNew').modal('hide')

              toast({
                type: 'success',
                title: 'เพิ่มผู้ใช้เรียบร้อยแล้ว'
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
                axios.get('api/findUser?q=' + query)
                .then((data) => {
                    this.users = data.data
                })
                .catch(() => {
                  
                })
            })
            this.loadUsers();
            Fire.$on('AfterCreate',() => {
              this.loadUsers();
            })
           // setInterval(() => this.loadUsers(), 3000);
        }
    }
</script>
