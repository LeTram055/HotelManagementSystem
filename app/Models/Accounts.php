<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
//use Illuminate\Database\Eloquent\Model;
//use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

//class Accounts extends Model implements \Illuminate\Contracts\Auth\Authenticatable
//class Accounts extends Model implements Authenticatable
class Accounts extends Authenticatable
{
    use HasFactory;
    protected $table = 'accounts';
    protected $fillable = [
        'account_username',
        'account_password',
        'account_role',
        'account_active',
    ];
    protected $guarded = ['account_id'];
    protected $primaryKey = 'account_id';
    public $timestamps = false;

    public function customers()
    {
        return $this->hasMany(Customers::class, 'account_id', 'account_id');
    }

    public function employees()
    {
        return $this->hasMany(Employees::class, 'account_id', 'account_id');
 
 
    }

    // Các phương thức xác thực bắt buộc
    // public function getAuthIdentifierName()
    // {
    //     return 'account_id'; // Trả về tên trường khóa chính
    // }

    // public function getAuthIdentifier()
    // {
    //     return $this->account_id; // Trả về giá trị khóa chính (ID)
    // }

    // public function getAuthPasswordName()
    // {
    //     return 'account_password'; // Tên trường mật khẩu trong bảng
    // }
    
    // public function getAuthPassword()
    // {
    //     return $this->account_password; // Trả về mật khẩu
    // }

    // public function getRememberToken()
    // {
    //     //return $this->remember_token;
    //     return null; // Trả về token nhớ đăng nhập (nếu có)
    // }

    // public function setRememberToken($value)
    // {
    //     null; // Do nothing
    // }

    // public function getRememberTokenName()
    // {
    //     return null; // Tên trường nhớ token (nếu có)
    // }

    // public function getAuthIdentifierName()
    // {
    //     return 'account_username'; // Tên cột xác thực (username)
    // }

    // public function getAuthIdentifier()
    // {
    //     return $this->account_id; // ID của tài khoản (cột khóa chính)
    // }

    // public function getAuthPasswordName()
    // {
    //     return 'account_password'; // Tên cột chứa mật khẩu
    // }

    // public function getAuthPassword()
    // {
    //     return $this->account_password;
    // }

    // public function getRememberToken()
    // {
    //     return null;
    // }

    // public function setRememberToken($value)
    // {
    //     // Do nothing
    // }

    // public function getRememberTokenName()
    // {
    //     return null;
    // }
}