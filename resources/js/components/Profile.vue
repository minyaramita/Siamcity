<template>
    <div class="container">
        <div class="row justify-content-center mt-3">


            <div class="col-md-12">

                <div class="card card-widget widget-user">
                    <!-- Add the bg color to the header using any of the bg-* classes -->
                    <div class="widget-user-header bg-info-active">
                        <h3 class="widget-user-username">Alexander Pierce</h3>
                        <h5 class="widget-user-desc">Founder &amp; CEO</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle elevation-2" src="" alt="User Avatar">
                    </div>
                </div>
            </div>


            <div class="col-md-12">
            <div class="card">
              <div class="card-header p-2">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">ประวัติผู้ใช้งาน</a></li>
                </ul>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content">
                  <div class="active tab-pane" id="settings">
                    <form class="form-horizontal">
                      <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">ชื่อ - นามสกุล</label>

                        <div class="col-sm-10">
                          <input v-model="form.name" type="text" class="form-control" id="name" placeholder="ชื่อ - นามสกุล">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="email" class="col-sm-2 control-label">อีเมล</label>

                        <div class="col-sm-10">
                          <input v-model="form.email" type="email" class="form-control" id="email" placeholder="อีเมล">
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="tel" class="col-sm-2 control-label">เบอร์โทรศัพท์</label>

                        <div class="col-sm-10">
                          <input v-model="form.tel" type="text" class="form-control" id="tel" placeholder="เบอร์โทรศัพท์">
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label for="photo" class="col-sm-2 control-label">รูปโปรไฟล์</label>
                        <div class="col-sm-10">
                            <input type="file" @change="updateProfile" name="photo" class="form-input" >
                        </div>
                      </div>

                      <div class="form-group">
                        <label for="password" class="col-sm-5 control-label">รหัสผ่าน (เว้นว่างไว้ หากไม่ต้องการเปลี่ยน)</label>

                        <div class="col-sm-10">
                          <input v-model="form.password" type="text" class="form-control" id="password" placeholder="รหัสผ่าน">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                          <button type="submit" class="btn btn-danger">อัพเดท</button>
                        </div>
                      </div>
                    </form>
                  </div>
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div><!-- /.card-body -->
            </div>
            <!-- /.nav-tabs-custom -->
          </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
          return {
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
        mounted() {
            console.log('Component mounted.')
        },

        methods:{
          updateProfile(e){
            //console.log('uploading');
            let file = e.target.files[0];
              // console.log(file);
              let reader = new FileReader();
              reader.onloadend = (file) => {
              // console.log('RESULT', reader.result)
              this.form.photo = reader.result;
              }
              reader.readAsDataURL(file);
          }
        },

        created() {
            axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        }
    }
</script>
