<?php
namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Accounts;
use App\Models\Customers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    // Đăng ký tài khoản khách hàng
    public function register(Request $request)
    {
        
        // $request->validate( [
        //     'account_username' => 'required|',
        //     'account_password' => 'required|min:8',
        //     'customer_name' => 'required|string',
        //     'customer_cccd' => 'required|string|unique:customers,customer_cccd',
        //     'customer_address' => 'required|string',
        // ]);

        // Kiểm tra xem username đã tồn tại chưa
        $existingAccount = Accounts::where('account_username', $request->account_username)->first();
        if ($existingAccount) {
            return response()->json(['message' => 'Tên tài khoản đã tồn tại'], 400);
        }

        // Kiểm tra xem khách hàng đã tồn tại chưa
        $existingCustomer = Customers::where('customer_cccd', $request->customer_cccd)->first();

        if ($existingCustomer) {
            // Kiểm tra nếu khách hàng đã có tài khoản
            if ($existingCustomer->account_id) {
                return response()->json(['message' => 'Khách hàng đã có tài khoản'], 400);
            } else {
                // Nếu chưa có tài khoản, tạo tài khoản và liên kết với khách hàng
                $account = Accounts::create([
                    'account_username' => $request->account_username,
                    'account_password' => Hash::make($request->account_password),
                    
                    'account_role' => 'customer',
                    'account_active' => 'active',
                ]);

                // Cập nhật account_id cho khách hàng
                $existingCustomer->update(['account_id' => $account->account_id, 'customer_email' => $request->customer_email]);

                return response()->json(['message' => 'Đăng ký thành công với tài khoản mới'], 201);
            }
        }

        // Nếu khách hàng chưa tồn tại, tạo tài khoản và khách hàng mới
        $account = Accounts::create([
            'account_username' => $request->account_username,
            'account_password' => Hash::make($request->account_password),

            'account_role' => 'customer',
            'account_active' => 'active',
        ]);

        $customer = Customers::create([
            'customer_name' => $request->customer_name,
            'customer_cccd' => $request->customer_cccd,
            'customer_email' => $request->customer_email,
            'customer_address' => $request->customer_address,
            'account_id' => $account->account_id,
        ]);

        return response()->json(['message' => 'Đăng ký thành công, tạo mới tài khoản và khách hàng'], 201);
    }

    // Đăng nhập cho khách hàng
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'account_username' => 'required',
            'account_password' => 'required',
        ]);

        
        // Tìm tài khoản theo tên đăng nhập
        $account = Accounts::where('account_username', $request->account_username)->first();

        // Kiểm tra tài khoản và mật khẩu hợp lệ
        if (!$account || !Hash::check($request->account_password, $account->account_password)) {
            return response()->json(['message' => 'Thông tin đăng nhập không chính xác'], 401);
        }

        // Kiểm tra trạng thái tài khoản
        if ($account->account_active !== 'active') {
            return response()->json(['message' => 'Tài khoản hiện không hoạt động'], 403);
        }

        // Kiểm tra vai trò là khách hàng
        if ($account->account_role != 'customer') {
            return response()->json(['message' => 'Tài khoản không thuộc quyền khách hàng'], 403);
        }

        // Tìm khách hàng liên kết với tài khoản này
        $customer = Customers::where('account_id', $account->account_id)->first();
        return response()->json(['message' => 'Đăng nhập thành công', 'customer' => $customer], 200);
    }

    // Lấy thông tin khách hàng đã đăng nhập
    public function getUserByUsername($username)
    {

        // Tìm tài khoản theo username
        $account = Accounts::where('account_username', $username)->first();

        if (!$account) {
            return response()->json(['message' => 'Tài khoản không tồn tại'], 404);
        }

        // Tìm khách hàng liên kết với tài khoản
        $customer = Customers::where('account_id', $account->account_id)->first();

        if (!$customer) {
            return response()->json(['message' => 'Không tìm thấy thông tin khách hàng'], 404);
        }

        return response()->json([
            'account' => $account,
            'customer' => $customer,
        ], 200);
    }

}