<template>
    <div class="container">
       <div class="row mt-3">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">สถานศึกษา</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" >
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
                  <tr>
                    <td>183</td>
                    <td>John Doe</td>
                    <td>11-7-2014</td>
                    <td><span class="tag tag-success">Approved</span></td>
                    <td>nnnn</td>
                    <td>nnnn</td>
                    <td>nnnn</td>
                    <td>nnnn</td>
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
              <div class="modal-body">
                ...
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">ปิด</button>
                <button type="submit" class="btn btn-primary">บันทึก</button>
              </div>
            </div>
          </div>
        </div>


    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
