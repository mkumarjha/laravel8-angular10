import { Component, OnInit } from '@angular/core';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { Permission } from 'src/app/interfaces/permission';
import { PermissionService } from 'src/app/services/permission.service';
import { RoleService } from 'src/app/services/role.service';

@Component({
  selector: 'app-role-create',
  templateUrl: './role-create.component.html',
  styleUrls: ['./role-create.component.css']
})
export class RoleCreateComponent implements OnInit {
  form!: FormGroup;
  permissions: Permission[] = [];
  
  constructor(
    private formBuilder: FormBuilder,
    private permissionService: PermissionService,
    private roleService: RoleService  ,
    private router: Router
  ) { }

  ngOnInit(): void {
    this.form = this.formBuilder.group({
      name:'',
      permissions: this.formBuilder.array([])
    });

    this.permissionService.all().subscribe(
      permissions=> {
        this.permissions = permissions;
        console.log(this.permissions);
        this.permissions.forEach(p=>{
          this.permissionArray.push(
            this.formBuilder.group({
              value: false,
              id: p.id
            })
          );
        });
      }
    );
  }

  get permissionArray(): FormArray{
    return this.form.get('permissions') as FormArray;
  }

  submit(): void {
    
    const formData = this.form.getRawValue();
    const data = {
      name: formData.name,
      permissions: formData.permissions.filter((p: any)=>p.value===true).map((p:any)=>p.id)
    };
    this.roleService.create(data)
      .subscribe(()=>this.router.navigate(['/roles']));
  }

}
