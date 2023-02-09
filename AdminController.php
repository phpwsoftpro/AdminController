<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use URL;
use App;
use DB; 
use Auth;
use Illuminate\Support\Str;
use file;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Course;
use App\Models\Setting;
use App\Models\Question;
use App\Models\Slider;
use App\Models\Service;
use App\Models\Concept;
use App\Models\ConceptField;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Topic;
use App\Models\SubTopic;
use App\Models\Topology;
use App\Models\Faculty_inform;
use App\Models\Student_inform;
use App\Models\Faculty_reg;
use App\Models\All_course;
use App\Models\Faculty_acc;
use App\Models\Staff;
use App\Models\Course_unit;
use App\Models\Library;
use App\Models\Addcoupon;
use App\Models\Test;


class AdminController extends Controller
{
 
    public function __construct(){
        $this->middleware('auth');
        $this->middleware('admin');
    }
    
#DASHBOARD

    public function index(){
        return view('admin.dashboard');
    }

 #UPLOAD QUESTION

    public function upload_question(){
		$questions = Test::get();
        return view('admin.course.upload_question',compact('questions'));
    }



 #TEST CODE START

    public function add_test(){
        return view('admin.course.add_test');
    }

 function add_test_action(Request $request){
	
$datas = New Test;

$datas['test_name']=$request->test_name;
$datas['p_mark']=$request->p_mark;
$datas['n_marks']=$request->n_marks;
$datas['marks']=$request->marks;
$datas['language']=$request->language;


$check = $datas->save();

if($check){
    
    return redirect('admin/add_test')->with('success', 'You have successfully added!');

}
else{
    
    return redirect('admin/add_test')->with('Failed', 'You have Not added!');
}

  }

	public function profile(){ 
        
		$profileInfo = Auth::user();
        return view('admin.profile',compact('profileInfo'));
    }
	
	public function update_profile(Request $request){
		
		$dataArray = array();
		
		$dataArray['name']  = $request->name;
		$dataArray['email'] = $request->email;
		
		User::where('id',Auth::user()->id)->where('role','admin')->update($dataArray);
		return redirect('/admin/profile')->with('success', 'Your profile has been updated successfully!');
	}
#TEST END


#EVENTS MANAGER  START


function event_manager(){
	return view('admin.event.event');
 }	


#FACULTY START


 public function faculty()
{
    $facaltys= Faculty_inform::all();
    
     return  view('admin.faculty.faculty',compact('facaltys'));
}
function view_profile(){
	return view('admin.faculty.view_profile');
 }


 function add_faculty(){
	return view('admin.faculty.add-faculty');
 }


 function upload_question_page(){
	return view('admin.course.upload_question_page');
 }


 function faculty_profile(){
	return view('admin.faculty.faculty-profile');
 }
	 
	public function add_faculty_action(Request $request)
   
   {

	$request->validate([
          
         'password' => 'min:6',
         'password_confirmation' => 'required_with:password|same:password|min:6',
         'account_no' =>  'required',
         'account_confirmation' =>  'required_with:account_no|same:account_no'



        ]);

 if(isset($request->img))
    {
        $imageName = time().'.'.$request->img->extension();  
        $request->img->move(public_path('uploads/admin/'), $imageName); 
    }
$datas = New Faculty_inform;

$datas['fname']=$request->fname;
$datas['lname']=$request->lname;
$datas['dob']=$request->dob;
$datas['gender']=$request->gender;
$datas['phone']=$request->phone;
$datas['email']=$request->email;
$datas['address']=$request->address;
$datas['qualification']=$request->qualification;
$datas['img']= $imageName;
$datas['about']=$request->about;
$datas['username']=$request->username;
$datas['password']=$request->password;
$datas['holder_name']=$request->holder_name;
$datas['ifsc']=$request->ifsc;
$datas['account_no']=$request->account_no;
$datas['upi']=$request->upi;

$check=$datas->save();
if($check){
    return redirect('admin/add_faculty')->with('success', 'You have successfully added!');

}
else{
    return redirect('admin/add_faculty')->with('Failed', 'You have Not added!');
}

  }
public function search_faculty(Request $request){
		 
       $facaltys = Faculty_inform::where('fname', 'LIKE', "%{$request->search}%")
       ->orWhere('phone', 'LIKE', "%{$request->search}%")
       ->orWhere('email', 'LIKE', "%{$request->search}%")
       ->get();
		$search_text = $request->search;
       if($facaltys) {
       	return view('admin.faculty.faculty',compact('facaltys','search_text'));
       } 
else{
    	return view('admin.faculty.faculty')->with('Failed', 'Result Not Found');
}
		
	}

#FACULTY END 



#STUDENTS START

	public function all_student(){ 

$students = Student_inform::select('student_informs.*','category.name as category_name')->leftJoin('category','category.id','=','student_informs.category')->get(); 

  return view('admin.student.list',compact('students'));
	
    }
	
public function search_student(Request $request){
		 
       $students = Student_inform::where('fname', 'LIKE', "%{$request->search}%")
       ->orWhere('phone', 'LIKE', "%{$request->search}%")
       ->orWhere('email', 'LIKE', "%{$request->search}%")
       ->get();
		$search_text = $request->search;
       if($students) {
       	return view('admin.student.list',compact('students','search_text'));
       } 
else{
    	return view('admin.student.list')->with('Failed', 'Result Not Found');
}
		
	}

  public function add_student(){ 
		$categry = Category::get();
        return view('admin.student.add_new',compact('categry'));
    }


      public function add_student_action(Request $request)
{
	$request->validate([
          
         'password' => 'min:5',
         'password_confirmation' => 'required_with:password|same:password|min:5'

        ]);

 if(isset($request->img)){
        $imageName = time().'.'.$request->img->extension();  
        $request->img->move(public_path('uploads/admin/'), $imageName); 
    }
$datas = New Student_inform;

$datas['fname']=$request->fname;
$datas['lname']=$request->lname;
$datas['phone']=$request->phone;
$datas['dob']=$request->dob;
$datas['gender']=$request->gender;
$datas['category']=$request->category;
$datas['email']=$request->email;
$datas['state']=$request->state;
$datas['city']=$request->city;
$datas['img']= $imageName;
$datas['email_phone']=$request->email_phone;
$datas['password']=$request->password;

$check = $datas->save();

if($check){
    return redirect('admin/add-student')->with('success', 'You have successfully added!');

}
else{
    return redirect('admin/add-student')->with('Failed', 'You have Not added!');
}

  }


function edit_student($id)
{
    // $student = Student_inform::where('id',$id)->first();
	$categry = Category::get();

$student = Student_inform::select('student_informs.*','category.name as category_name')->leftJoin('category','category.id','=','student_informs.category')->where('student_informs.id',$id)->first(); 

  return view('admin.student.edit',compact('student','categry'));
}


public function edit_student_action(Request $request){
        

    $dataArray = array();
  
    $dataArray['fname']  = $request->fname;
    $dataArray['lname'] = $request->lname;
    $dataArray['phone']  = $request->phone;
    $dataArray['email']      = $request->email;
    $dataArray['gender']  = $request->gender;
    $dataArray['dob'] = $request->dob;
    $dataArray['category'] = $request->category;
    $dataArray['state'] = $request->state;
    $dataArray['city'] = $request->city;

     if(isset($request->img)){
        
        $student = Student_inform::where('id',$request->student_id)->first();
        if(!empty($student->img)){
            $getFilePath = public_path('uploads/admin/').$student->img;
            if(file_exists($getFilePath)){
                unlink($getFilePath);
            }
        }
        $imageName = time().'.'.$request->img->extension();  
        $request->img->move(public_path('uploads/admin/'), $imageName); 
        $dataArray['img'] = $imageName;
    }

    $dataArray['email_phone'] = $request->email_phone;
    $dataArray['password'] = $request->password;
    
    Student_inform::where('id',$request->student_id)->update($dataArray);
    return redirect()->back();
}





	
	public function delete_student($user_id){
		
		$unserInfo = User::where('id',$user_id)->first();
		if(!empty($unserInfo->image)){
			$getFilePath = public_path('uploads/student/').$unserInfo->image;
			if(file_exists($getFilePath)){
				unlink($getFilePath);
			}
		}
		User::where('id',$user_id)->delete();
        return redirect('/admin/students')->with('success', 'You have successfully deleted!');
    }
	
	function student_profile(){
		return view('admin.student.profile');
	 }
	 function student_invoice(){
		return view('admin.student.invoice');
	 }
#STUDENTS END 


#GLOBAL SETTING START

	public function global_setting(){
		
		$settingInfo = Setting::first();
		return view('admin.setting',compact('settingInfo'));
	}
	public function global_setting_action(Request $request){
		
		if(empty($request->setting_id)){
			
			if(isset($request->header_logo)){
				$headerLogo = time().'.'.$request->header_logo->extension();  
				$request->header_logo->move(public_path('uploads/setting/'), $headerLogo);  
			}
			
			if(isset($request->footer_logo)){
				$footerLogo = time().'.'.$request->footer_logo->extension();  
				$request->footer_logo->move(public_path('uploads/setting/'), $footerLogo);  
			}
			
			$setting = new Setting;
			$setting->admin_email     = $request->email;
			
			if(!empty($headerLogo)){
			  $setting->header_logo    = $headerLogo;
			}
			$setting->header_contact_details     = $request->header_contact_details;
			
			if(!empty($footerLogo)){
			  $setting->footer_logo    = $footerLogo;
			}
			$setting->footer_description      = $request->footer_description;
			$setting->footer_social_media     = $request->footer_social_media;
			$setting->footer_copyright        = $request->footer_copyright;
			$setting->footer_contact_details  = $request->footer_contact_details;
			$setting->save();
			
			return redirect('/admin/setting')->with('success', 'You have successfully added!');
			
		}else{
			
			if(isset($request->header_logo)){
				$headerLogo = time().'.'.$request->header_logo->extension();  
				$request->header_logo->move(public_path('uploads/setting/'), $headerLogo);  
			}
			
			if(isset($request->footer_logo)){
				$footerLogo = time().'.'.$request->footer_logo->extension();  
				$request->footer_logo->move(public_path('uploads/setting/'), $footerLogo);  
			}
			$settingArray = array();
			$settingArray['admin_email']     = $request->email;
			
			if(!empty($headerLogo)){
			  $settingArray['header_logo']    = $headerLogo;
			}
			$settingArray['header_contact_details']     = $request->header_contact_details;
			
			if(!empty($footerLogo)){
			  $settingArray['footer_logo']    = $footerLogo;
			}
			$settingArray['footer_description']      = $request->footer_description;
			$settingArray['footer_social_media']     = $request->footer_social_media;
			$settingArray['footer_copyright']        = $request->footer_copyright;
			$settingArray['footer_contact_details']  = $request->footer_contact_details;
			
			Setting::where('id',$request->setting_id)->update($settingArray);
			return redirect('/admin/setting')->with('success', 'You have successfully updated!');	
		}
	}
	#GLOBAL SETTING

	#DEVICE LINKING POLICY

	public function device_linking_policy(){
        return view('admin.device_linking_policy'); 
    }
	
	#CUSTOMER LOG
	public function customer_log(){
        return view('admin.customer_log');
    }
	
	#SLIDER
	public function all_slider(){
		
		$sliders = Slider::get();
        return view('admin.slider.list',compact('sliders'));
    }
	
	public function add_slider(){ 
        return view('admin.slider.add');
    }
	
	public function add_slider_action(Request $request){
		
		$request->validate([
            'title'          => ['required'],
			'image'          => 'image|mimes:jpg,png,jpeg,gif,svg',
            'description'    => ['required'],
        ]);
		
		if(isset($request->image)){
		    $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/slider/'), $imageName); 
		}else{
			$imageName = '';
		}
		
		$slider = new Slider;
		$slider->title          = $request->title;
		$slider->image         = $imageName;
		$slider->button_text         = $request->button_text;
		$slider->button_link         = $request->button_link;
		$slider->description   = $request->description;
		$slider->status   = $request->status;
		$slider->save();
		
        return redirect('/admin/slider')->with('success', 'You have successfully added!');
    }
	
	public function edit_slider($slider_id){
		
		$sliderInfos = Slider::where('id',$slider_id)->first();
        return view('admin.slider.edit',compact('sliderInfos'));
	}
	
	public function edit_slider_action(Request $request){
		
		$request->validate([
            'title'           => ['required'],
            'description'    => ['required'],
        ]);
		 
		
		$dataArray = array();
		$dataArray['title']        = $request->title;
		$dataArray['description'] = $request->description;
		$dataArray['button_text']      = $request->button_text;
		$dataArray['button_link']      = $request->button_link;
		$dataArray['status']      = $request->status;
		
		if(isset($request->image)){
			
			$catInfo = Slider::where('id',$request->slider_id)->first();
			if(!empty($catInfo->image)){
				$getFilePath = public_path('uploads/slider/').$catInfo->image;
				if(file_exists($getFilePath)){
					unlink($getFilePath);
				}
			}
			
		    $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/slider/'), $imageName); 
			$dataArray['image'] = $imageName;
		}
		
	 
		
		Slider::where('id',$request->slider_id)->update($dataArray);

        return redirect('/admin/slider')->with('success', 'You have successfully updated!');
    }
	
	public function delete_slider($slider_id){
		
		$catInfo = Slider::where('id',$slider_id)->first();
		if(!empty($catInfo->image)){
			$getFilePath = public_path('uploads/category/').$catInfo->image;
			if(file_exists($getFilePath)){
				unlink($getFilePath);
			}
		}
		Slider::where('id',$slider_id)->delete();
		return redirect('/admin/slider')->with('success', 'You have successfully deleted!');
	}
     #SLIDER END


	#CATEGORY START
	public function all_category(){
		
		$category = Category::get();
        return view('admin.category.category',compact('category'));
    }


	
	public function add_category(){ 
        return view('admin.category.add_category');
    }
	
	public function add_category_action(Request $request){
		
		
	  $exsist=Category::where('name',$request->name)->first();
         if($exsist){
         return redirect('/admin/add-category')->with('error', 'This Category is Already exsist!');
		}


		$slug  = Str::slug($request->name);
        $count = Category::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		
		// if(isset($request->image)){
		//     $imageName = time().'.'.$request->image->extension();  
        //     $request->image->move(public_path('uploads/category/'), $imageName); 
		// }else{
		// 	$imageName = '';
		// }
		
		$category = new Category;
		$category->name          = $request->name;
		$category->slug          = $slug;
		// $category->image         = $imageName;
		// $category->description   = $request->description;
		$category->save();
		
        return redirect('/admin/add-category')->with('success', 'You have successfully added!');
    }
	
public function search_category(Request $request){
		 
       $category = Category::where('name', 'LIKE', "%{$request->search}%")->get();      
		$search_text = $request->search;
       if($category) {
       	return view('admin.category.category',compact('category','search_text'));
       } 
else{
    	return redirect('/admin/category')->with('success', 'You have successfully added!');
}
		
	}


	public function edit_category($category_id){
		
		$categoryInfo = Category::where('id',$category_id)->first();
        return view('admin.category.edit_category',compact('categoryInfo'));
	}
	
	public function edit_category_action(Request $request){
		
		$request->validate([
            'name'           => ['required'],
             
        ]);
		$exsist=Category::where('name',$request->name)->first();
         if($exsist){
         return redirect('/admin/category')->with('error', 'This Class is Already exsist!');
		}

		$getSlug    = \Str::slug($request->name);
        $getSlugRes = Category::where('slug',$getSlug)->whereNotIn('id',array($request->category_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/category')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;
 
		
		// if(isset($request->image)){
			
		// 	$catInfo = Category::where('id',$request->category_id)->first();
		// 	if(!empty($catInfo->image)){
		// 		$getFilePath = public_path('uploads/category/').$catInfo->image;
		// 		if(file_exists($getFilePath)){
		// 			unlink($getFilePath);
		// 		}
		// 	}
			
		//     $imageName = time().'.'.$request->image->extension();  
        //     $request->image->move(public_path('uploads/category/'), $imageName); 
		// 	$dataArray['image'] = $imageName;
		// }
		
		$dataArray['status']      = $request->status;
		$dataArray['slug']        = $getSlug;
		
		Category::where('id',$request->category_id)->update($dataArray);

        return redirect('/admin/category')->with('success', 'You have successfully updated!');
    }
	
	public function delete_category($category_id){
		
		$catInfo = Category::where('id',$category_id)->first();
		if(!empty($catInfo->image)){
			$getFilePath = public_path('uploads/category/').$catInfo->image;
			if(file_exists($getFilePath)){
				unlink($getFilePath);
			}
		}
		Category::where('id',$category_id)->delete();
		return redirect('/admin/category')->with('success', 'You have successfully deleted!');
	}
	#CATEGORY END 
	 

	#SUBCATEGORY START 

	public function all_sub_category(){	
		   $category = Subcategory::select('sub_category.*','category.name as parent_name')->leftjoin('category','category.id','=','sub_category.category_id')->get();
		 
		return view('admin.sub-category.category',compact('category'));
    }
	public function add_sub_category(){	
		$categories = Category::get();
		return view('admin.sub-category.add_category',compact('categories'));	
    }
	 
	public function add_sub_category_action(Request $request){
      
      $exsist=Subcategory::where('name',$request->name)->first();
         if($exsist){
         return redirect('/admin/add-board')->with('error', 'This board is Already exsist!');
		}

		$slug  = Str::slug($request->name);
        $count = Subcategory::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		 
		$category = new Subcategory;
		$category->name          = $request->name;
		$category->slug          = $slug;
		$category->category_id   = $request->category_id;
		$category->save();
		
        return redirect('/admin/add-board')->with('success', 'You have successfully added!');
    }
	
	 

	public function edit_sub_category($category_id){
		$category = Category::get();
		$subcategory = Subcategory::where('id',$category_id)->first();
		return view('admin.sub-category.edit_category',compact('category','subcategory'));
    }

	public function edit_sub_category_action(Request $request){
		
		$request->validate([
            'name'           => ['required'],
             
        ]);
          $exsist=Subcategory::where('name',$request->name)->first();
         if($exsist){
         return redirect('/admin/all-board')->with('error', 'This Class is Already exsist!');
		}

		
		$getSlug    = \Str::slug($request->name);
        $getSlugRes = Subcategory::where('slug',$getSlug)->whereNotIn('id',array($request->subcategory_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/all-board')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;
		$dataArray['category_id'] = $request->category_id;
		$dataArray['status']      = $request->status;
		$dataArray['slug']        = $getSlug;
		
		Subcategory::where('id',$request->subcategory_id)->update($dataArray);

        return redirect('/admin/all-board')->with('success', 'You have successfully updated!');
    }
	public function delete_sub_category($category_id){
		
		$catInfo = Subcategory::where('id',$category_id)->first();
		 
		Subcategory::where('id',$category_id)->delete();
		return redirect('/admin/all-board')->with('success', 'You have successfully deleted!');
	}


	public function search_board(Request $request){
	

       $category = Subcategory::select('sub_category.*','category.name as parent_name')->leftjoin('category','category.id','=','sub_category.category_id')->where('sub_category.name', 'LIKE', "%{$request->search}%")->get();
		$search_text = $request->search;
       if($category) {
       	return view('admin.sub-category.category',compact('category','search_text'));
       } 
else{
    	return redirect('/admin/all-board')->with('error', 'No result found');
}
		
	}

	#SUBCATEGORY END


	#CLASS START

	public function search_class(Request $request)
	{
        $data = ClassModel::select('class.*','sub_category.name as board_name')->leftjoin('sub_category','sub_category.id','=','class.board_id')->where('class.name', 'LIKE', "%{$request->search}%")->get();
		$search_text = $request->search;
       if($data) {
       	return view('admin.class.class',compact('data','search_text'));
       } 
    else{
    	return redirect('/admin/add-class')->with('error', 'No result found');
      }
		
	 }

	public function all_class(){	
		$data = ClassModel::get();
          $data = ClassModel::select('class.*','sub_category.name as board_name')->leftjoin('sub_category','sub_category.id','=','class.board_id')->get();
		return view('admin.class.class',compact('data'));
    }

	public function add_class(){	
		$categories = Category::get();
		$board = SubCategory::get();
		return view('admin.class.add_class',compact('categories','board'));
    }

	public function add_class_action(Request $request){	
		
        $exsist=ClassModel::where('name',$request->name)->first();
         if($exsist){
         return redirect('/admin/add-class')->with('error', 'This Class is Already exsist!');
		}

		$slug  = Str::slug($request->name);
        $count = ClassModel::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		$data = new ClassModel;
		$data->name = $request->name ;
		$data->category_id = $request->category_id ;
		$data->board_id = $request->board_id ;
		$data->slug = $slug ;
		$data->save();

	return redirect('/admin/add-class')->with('success', 'You have successfully Added!');
    }

	public function edit_class($id){
		$category = Category::get();
		$board = SubCategory::get();
    $data = ClassModel::where('id',$id)->first();
		return view('admin.class.edit_class',compact('data','category','board'));
    }
	public function edit_class_action(Request $request){	
		$getSlug    = Str::slug($request->name);
        $getSlugRes = ClassModel::where('slug',$getSlug)->whereNotIn('id',array($request->class_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/all-class')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['category_id']       =  $request->category_id;		 
		$dataArray['board_id']        =  $request->board_id;		 
		$dataArray['name']        =  $request->name;		 
		$dataArray['slug']        =  $getSlug;
		$dataArray['status']        =  $request->status;

		
		ClassModel::where('id',$request->class_id)->update($dataArray); 
		return redirect('/admin/all-class')->with('success', 'You have successfully Updated!');
    }
	public function delete_class($class_id){

		ClassModel::where('id',$class_id)->delete();
		return redirect('/admin/all-class')->with('success', 'You have successfully deleted!');
	}
	#CLASS END 

	#SUBJECT START

	public function all_subject(){	
		$data = Subject::get();
		return view('admin.subject.subject',compact('data'));
    }
	public function add_subject(){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
		return view('admin.subject.add_subject',compact('category','board','class'));
    }
	public function add_subject_action(Request $request){	
		
         $exsist=Subject::where('name',$request->name)->first();
         if($exsist){
         return redirect('/admin/add-subject')->with('error', 'This Subject is Already exsist!');
		 }

		$slug  = Str::slug($request->name);
        $count = Subject::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		$data = new Subject;
		$data->name = $request->name ;
		$data->category_id = $request->category_id ;
		$data->board_id = $request->board_id ;
		$data->class_id = $request->class_id ;
		$data->slug = $slug ;
		$data->save();

	return redirect('/admin/add-subject')->with('success', 'You have successfully Added!');
    }
	public function edit_subject($id){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
		$data = Subject::where('id',$id)->first();
		return view('admin.subject.edit_subject',compact('data','category','board','class'));
    }

	public function edit_subject_action(Request $request){	
		$getSlug    = Str::slug($request->name);
        $getSlugRes = Subject::where('slug',$getSlug)->whereNotIn('id',array($request->subject_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/subject')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['category_id']        = $request->category_id;		 
		$dataArray['board_id']        = $request->board_id;		 
		$dataArray['class_id']        = $request->class_id;		 
		$dataArray['name']        = $request->name;		 
		$dataArray['slug']        = $getSlug;
		$dataArray['status']        = $request->status;		 

		
		Subject::where('id',$request->subject_id)->update($dataArray); 
		return redirect('/admin/subject')->with('success', 'You have successfully Updated!');
    }
	public function delete_subject($subject_id){
		 
		Subject::where('id',$subject_id)->delete();
		return redirect('/admin/subject')->with('success', 'You have successfully deleted!');
	}


public function search_subject(Request $request){
		 
       $data = Subject::where('name', 'LIKE', "%{$request->search}%")->get();      
		$search_text = $request->search;
       if($data) {
       	return view('admin.subject.subject',compact('data','search_text'));
       } 
else{
    	return redirect('/admin/subject')->with('error', 'No Result Found!');
}
		
	}


#SUBJECT END 


#TOPIC START

	public function all_topic(){
		$data = Topic::get();
			
		return view('admin.topic.list',compact('data'));
    }
	public function add_topic(){
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();	
        $subject = Subject::get();	
		return view('admin.topic.add',compact('category','board','class','subject'));
    }
	public function add_topic_action(Request $request){	
		
		$slug  = Str::slug($request->name);
        $count = Topic::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		$data = new Topic;
		$data->name = $request->name ;
		$data['category_id']        = $request->category_id;		 
		$data['board_id']        = $request->board_id;		 
		$data['class_id']        = $request->class_id;	
		$data['subject_id']        = $request->subject_id;	
		$data->slug = $slug ;
		$data->save();

	return redirect('/admin/add-topic')->with('success', 'You have successfully Added!');
    }
	public function edit_topic($id){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();	
        $subject = Subject::get();
		$data = Topic::where('id',$id)->first();
		return view('admin.topic.edit',compact('data','category','board','class','subject'));
    }
	public function edit_topic_action(Request $request){	
		$getSlug    = Str::slug($request->name);
        $getSlugRes = Topic::where('slug',$getSlug)->whereNotIn('id',array($request->topic_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/topic')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;	
		$dataArray['category_id']        = $request->category_id;		 
		$dataArray['board_id']        = $request->board_id;		 
		$dataArray['class_id']        = $request->class_id;	
		$dataArray['subject_id']        = $request->subject_id;	 
		$dataArray['slug']        = $getSlug;
		
		Topic::where('id',$request->topic_id)->update($dataArray); 
		return redirect('/admin/topic')->with('success', 'You have successfully Updated!');
    }
	public function delete_topic($topic_id){
		 
		Topic::where('id',$topic_id)->delete();
		return redirect('/admin/topic')->with('success', 'You have successfully deleted!');
	}



	#TOPIC END  


	#SUBTOPIC START

	public function all_sub_topic(){	
		$subtopic = SubTopic::get();
		 
		return view('admin.sub-topic.list',compact('subtopic'));
    }
	public function add_sub_topic(){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
        $subject = Subject::get();
        $topic = Topic::get();
		return view('admin.sub-topic.add',compact('category','board','class','subject','topic'));
    }

	public function add_sub_topic_action(Request $request){	
		
		$slug  = Str::slug($request->name);
        $count = SubTopic::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		$data = new SubTopic;
		$data->name = $request->name ;
		$data['category_id']        = $request->category_id;		 
		$data['board_id']        = $request->board_id;		 
		$data['class_id']        = $request->class_id;	
		$data['subject_id']        = $request->subject_id;	
		$data['topic_id']        = $request->topic_id;	
		$data->slug = $slug ;
		$data->save();
	return redirect('/admin/add-sub_topic')->with('success', 'You have successfully Added!');
    }
	public function edit_sub_topic($id){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
        $subject = Subject::get();
        $topic = Topic::get();
		$data = SubTopic::where('id',$id)->first();
		return view('admin.sub-topic.edit',compact('data','category','board','class','subject','topic'));
    }

	public function edit_sub_topic_action(Request $request){	
		$getSlug    = Str::slug($request->name);
        $getSlugRes = SubTopic::where('slug',$getSlug)->whereNotIn('id',array($request->subtopic_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/sub_topic')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;	
		$dataArray['category_id']        = $request->category_id;		 
		$dataArray['board_id']        = $request->board_id;		 
		$dataArray['class_id']        = $request->class_id;	
		$dataArray['subject_id']        = $request->subject_id;	 
		$dataArray['topic_id']        = $request->topic_id;	 
		$dataArray['slug']        = $getSlug;
		
		SubTopic::where('id',$request->subtopic_id)->update($dataArray); 
		return redirect('/admin/sub_topic')->with('success', 'You have successfully Updated!');
    }
	public function delete_sub_topic($id){
		 
		SubTopic::where('id',$id)->delete();
		return redirect('/admin/sub_topic')->with('success', 'You have successfully deleted!');
	}

    #SUBTOPIC END 


	#CONCEPT START

	public function all_concept(){	
		$concept = Concept::get();
		return view('admin.concept.list',compact('concept'));
    }
	public function add_concept(){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
        $subject = Subject::get();
        $topic = Topic::get();
        $subtopic = SubTopic::get();
	  return view('admin.concept.add',compact('category','board','class','subject','topic','subtopic'));
    }
    
	public function add_concept_action(Request $request){	
		
		$slug  = Str::slug($request->name);
        $count = Concept::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		$data = new Concept;
		$data->name = $request->name ;
		$data['category_id']        = $request->category_id;		 
		$data['board_id']        = $request->board_id;		 
		$data['class_id']        = $request->class_id;	
		$data['subject_id']        = $request->subject_id;	
		$data['topic_id']        = $request->topic_id;	
		$data['subtopic_id']        = $request->subtopic_id;	
		$data->slug = $slug ;
		$data->save();

	return redirect('/admin/concept')->with('success', 'You have successfully Added!');
    }
	public function edit_concept($id){	
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
        $subject = Subject::get();
        $topic = Topic::get();
        $subtopic = SubTopic::get();
		$data = Concept::where('id',$id)->first();
		return view('admin.concept.edit',compact('data','category','board','class','subject','topic','subtopic'));
    }

	public function edit_concept_action(Request $request){	
		$getSlug    = Str::slug($request->name);
        $getSlugRes = Concept::where('slug',$getSlug)->whereNotIn('id',array($request->concept_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/concept')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;	
		$dataArray['category_id']        = $request->category_id;		 
		$dataArray['board_id']        = $request->board_id;		 
		$dataArray['class_id']        = $request->class_id;	
		$dataArray['subject_id']        = $request->subject_id;	 
		$dataArray['topic_id']        = $request->topic_id;	 
		$dataArray['subtopic_id']        = $request->subtopic_id;	 
		$dataArray['slug']        = $getSlug;
		
		Concept::where('id',$request->concept_id)->update($dataArray); 
		return redirect('/admin/concept')->with('success', 'You have successfully Updated!');
    }

	public function delete_concept($id){
		 
		Concept::where('id',$id)->delete();
		return redirect('/admin/concept')->with('success', 'You have successfully deleted!');
	}
       #CONCEPT END 



		#CONCEPT FIELD START

		public function all_concept_field(){	
			$concept_field = ConceptField::get();
			return view('admin.concept-field.list',compact('concept_field'));
		}
		public function add_concept_field(){	
			$category = Category::get();
			$board = SubCategory::get();
			$class = ClassModel::get();
			$subject = Subject::get();
			$topic = Topic::get();
			$subtopic = SubTopic::get();
			$concept = Concept::get();
			return view('admin.concept-field.add',compact('category','board','class','subject','topic','subtopic','concept'));
		}
	
		public function add_concept_field_action(Request $request){	
			
			$slug  = Str::slug($request->name);
			$count = ConceptField::where('slug',$slug)->count();
			if($count>0){
				$slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
			}
			$data = new ConceptField;
			$data->name = $request->name ;
			$data['category_id']        = $request->category_id;		 
			$data['board_id']        = $request->board_id;		 
			$data['class_id']        = $request->class_id;	
			$data['subject_id']        = $request->subject_id;	
			$data['topic_id']        = $request->topic_id;	
			$data['subtopic_id']        = $request->subtopic_id;	
			$data['concept_id']        = $request->concept_id;	
			$data->slug = $slug ;
			// dd($data);
			$data->save();
	
		return redirect('/admin/add-concept_field')->with('success', 'You have successfully Added!');
		}
		public function edit_concept_field($id){	
			$category = Category::get();
			$board = SubCategory::get();
			$class = ClassModel::get();
			$subject = Subject::get();
			$topic = Topic::get();
			$subtopic = SubTopic::get();
			$concept = Concept::get();
			$data = ConceptField::where('id',$id)->first();
			return view('admin.concept-field.edit',compact('data','category','board','class','subject','topic','subtopic','concept'));
		}
	
		public function edit_concept_field_action(Request $request){	
			$getSlug    = Str::slug($request->name);
			$getSlugRes = ConceptField::where('slug',$getSlug)->whereNotIn('id',array($request->concept_field_id))->count();
			
			if($getSlugRes>0){
				return redirect('/admin/concept_field')->with('error', 'This name is all ready taken!!');
			}
			
			$dataArray = array();
			$dataArray['name']        = $request->name;	
			$dataArray['category_id']        = $request->category_id;		 
			$dataArray['board_id']        = $request->board_id;		 
			$dataArray['class_id']        = $request->class_id;	
			$dataArray['subject_id']        = $request->subject_id;	 
			$dataArray['topic_id']        = $request->topic_id;	 
			$dataArray['subtopic_id']        = $request->subtopic_id;	 
			$dataArray['concept_id']        = $request->concept_id;	 
			$dataArray['slug']        = $getSlug;
			
			ConceptField::where('id',$request->concept_field_id)->update($dataArray); 
			return redirect('/admin/concept_field')->with('success', 'You have successfully Updated!');
		}
	
		public function delete_concept_field($id){
			 
			ConceptField::where('id',$id)->delete();
			return redirect('/admin/concept_field')->with('success', 'You have successfully deleted!');
		}

       #CONCEPT FIELD END


	   #TOPOLOGY START 

	  public function filter_topology(Request $request){	
		$category = $request->category;
		$board = $request->board;
		$class = $request->class;
		$subject = $request->subject;
	 
		 

		// if ($request->category) {
			 
		// 	$data = $data->where('topology.category_id', '=', $category);			
		// }
		// if ($request->board) {
			 
		// 	$data = $data->where('topology.board_id', '=', $board);			
		// }
		// if ($request->class) {
			 
		// 	$data = $data->where('topology.class_id', '=', $class);			
		// }
		// if ($request->subject) {
			 
		// 	$data = $data->where('topology.subject_id', '=', $subject);			
		// }
		 
		 
		$data = Topology::select('topology.*','subjects.name as subject','class.name as class','sub_category.name as board','category.name as category')
		->join('subjects','subjects.id','=','topology.subject_id')
		->join('class','class.id','=','topology.class_id')
		->join('sub_category','sub_category.id','=','topology.board_id')
		->join('category','category.id','=','topology.category_id')
		->where('topology.category_id', '=', $category)
		->orWhere('topology.board_id', '=', $board)
		->orWhere('topology.class_id', '=', $class)
		->orWhere('topology.subject_id', '=', $subject)->get();
        //   dd($data);
		$category = Category::get();
		$board = SubCategory::get();
		$class = ClassModel::get();
		$subject = Subject::get();
		return view('admin.topology',compact('data','category','board','class','subject'));
    }

	public function topology(){
		$category = Category::get();
		$board = SubCategory::get();
		$class = ClassModel::get();
		$subject = Subject::get();
		$data = Topology::select('topology.*','subjects.name as subject','class.name as class','sub_category.name as board','category.name as category')->join('subjects','subjects.id','=','topology.subject_id')->join('class','class.id','=','topology.class_id')->join('sub_category','sub_category.id','=','topology.board_id')->join('category','category.id','=','topology.category_id')->get();
		// dd($data);
        return view('admin.topology',compact('data','category','board','class','subject'));
    }

	public function add_topology(){	
		$category = Category::get();
		$board = SubCategory::get();
		$class = ClassModel::get();
		$subject = Subject::get();
		return view('admin.topology.add',compact('category','board','class','subject'));
    }

	public function add_topology_action(Request $request){	
		 
		$data = new Topology; 		 
		$data['category_id']        = $request->category_id;		 
		$data['board_id']        = $request->board_id;		 
		$data['class_id']        = $request->class_id;	
		$data['subject_id']        = $request->subject_id;	
		$data['topic']        = $request->topic;	
		$data['subtopic']        = $request->subtopic;	
		$data['concept']        = $request->concept;	
		$data['concept_field']        = $request->concept_field;	
		 	
	 
		// dd($data);
		$data->save();

	return redirect('/admin/add-topology')->with('success', 'You have successfully Added!');
	}

	 

	public function edit_topology($id){	
		$category = Category::get();
		$board = SubCategory::get();
		$class = ClassModel::get();
		$subject = Subject::get();
		$data = Topology::where('id',$id)->first();
		return view('admin.topology.edit',compact('data','category','board','class','subject'));
	}

	public function edit_topology_action(Request $request){	
		 
		
		$dataArray = array();
		$dataArray['category_id']        = $request->category_id;		 
		$dataArray['board_id']        = $request->board_id;		 
		$dataArray['class_id']        = $request->class_id;	
		$dataArray['subject_id']        = $request->subject_id;	
		$dataArray['topic']        = $request->topic;	
		$dataArray['subtopic']        = $request->subtopic;	
		$dataArray['concept']        = $request->concept;	
		$dataArray['concept_field']        = $request->concept_field;
		
		Topology::where('id',$request->topology_id)->update($dataArray); 
		return redirect('/admin/topology')->with('success', 'You have successfully Updated!');
	}

     #TOPOLOGY END 



	#DIFFICULTY LEVEL START 

	public function all_difficulty(){	
		return view('admin.difficulty.list');
    }
	public function add_difficulty(){	
		return view('admin.difficulty.add');
    }
	public function edit_difficulty(){	
		return view('admin.difficulty.edit');
    }
    #DIFFICULTY LEVEL END


	#COUPON CODE START

	public function all_coupon(){	

    $coupons = Addcoupon::orderby('id','DESC')->where('status',1)->get();
    return view('admin.coupon.list',compact('coupons'));
    }


    public function delete_coupon($id)
    {
    Addcoupon::where('id',$id)->delete();
    return redirect('admin/add-coupon');
    }

    public function del_coupon($id)
    {
    Addcoupon::where('id',$id)->delete();
    return redirect('admin/coupon');
    }
	public function add_coupon(){

    $coupons = Addcoupon::orderby('id','DESC')->where('status',0)->get();
    return view('admin.coupon.add',compact('coupons'));
    
    }
	
public function search_coupon(Request $request){
		 
       $coupons = Addcoupon::where('email', 'LIKE', "%{$request->search}%")
       ->orWhere('phone', 'LIKE', "%{$request->search}%")
       ->orWhere('your_id', 'LIKE', "%{$request->search}%")
       ->get();
		$search_text = $request->search;
       if($coupons) {
       	return view('admin.coupon.add',compact('coupons','search_text'));
       } 
else{
    	return redirect('/admin/add-coupon')->with('success', 'You have successfully added!');
}
		
	}

public function search_coupon_list(Request $request){
		 
       $coupons = Addcoupon::where('email', 'LIKE', "%{$request->search}%")
       ->orWhere('phone', 'LIKE', "%{$request->search}%")
       ->orWhere('your_id', 'LIKE', "%{$request->search}%")
       ->get();
		$search_text = $request->search;
       if($coupons) {
       	return view('admin.coupon.list',compact('coupons','search_text'));
       } 
else{
    	return redirect('/admin/coupon')->with('success', 'You have successfully added!');
}
		
	}


	public function edit_coupon($id){	

		$cupon = Addcoupon::where('id',$id)->first();
		return view('admin.coupon.edit',compact('cupon'));

    }

public function edit_coupon_action(Request $request){

    $dataArray = array();
    $dataArray['name']  = $request->name;
    $dataArray['phone'] = $request->phone;
    $dataArray['email']  = $request->email;
    $dataArray['your_id'] = $request->your_id;
    $dataArray['status'] = $request->status;
    $dataArray['discount'] = $request->discount;
    $dataArray['coupon'] = $request->coupon;
    
    Addcoupon::where('id', $request->cupon_id)->update($dataArray);
    return redirect()->back();
}
   #COUPON CODE END


	#REPORTS START 

	public function total_income(){	
		return view('admin.reports.total-income');
    }
	public function report_sales(){	
		return view('admin.reports.sales');
    }
	public function report_student_payment(){	
		return view('admin.reports.student-report');
    }
   
    #REPORTS END

	#PAYMENT START

	public function all_payment(){	
		return view('admin.payment.all');
    }
	public function student_payment(){	
		return view('admin.payment.student');
    }
	public function teacher_payment(){	
		return view('admin.payment.teacher');
    }
	public function team_payment(){	
		return view('admin.payment.team');
    }
    #PAYMENT END

	#EDUCATIONAL NEWS SECTION START

	public function education_news(){	
		return view('admin.education-news');
    }
    #EDUCATIONAL NEWS SECTION END

	#Notification
	public function all_notification(){	
		return view('admin.notification.list');
	}
	public function add_notification(){	
		return view('admin.notification.add');
	}
	public function edit_notification(){	
		return view('admin.notification.edit');
	}
	#Notification END


	#SERVICES START
 
	public function all_service(){
		
		$services = Service::get();
        return view('admin.services.services',compact('services'));
    }
	
	public function add_service(){ 
		$category = Category::get();
		return view('admin.services.add_new_service',compact('category'));
    }

	public function add_service_action(Request $request){
		 
		$request->validate([
            'name'           => ['required'],
			//'image'          => ['required','image|mimes:jpg,png,jpeg,gif,svg'],
			'image'          => ['required'],          
            'category_id'    => ['required'],
            'description'    => ['required'],
        ]);
		
		$slug  = Str::slug($request->name);
        $count = Service::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		 
		if(isset($request->image)){
		    $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/service/'), $imageName); 
		}else{
			$imageName = '';
		}
		 
		$service = new Service;
		$service->name          = $request->name;
		$service->slug          = $slug;
		$service->status          = $request->status;
		$service->image         = $imageName;
		$service->category_id   = $request->category_id;
		$service->short_description   = $request->short_description;
		$service->description   = $request->description;
		$service->save(); 
		
        return redirect('/admin/service')->with('success', 'You have successfully added!');
    }

	public function edit_service($service_id){
		
		$category = Category::get();
		$service = Service::where('id',$service_id)->first();
        return view('admin.services.edit_service',compact('service','category'));
	}

	public function edit_service_action(Request $request){
		
		$request->validate([
            'name'           => ['required'],
			//'image'          => ['required','image|mimes:jpg,png,jpeg,gif,svg'],
            
            'category_id'    => ['required'],
            'description'    => ['required'],
        ]);
		
		$getSlug    = Str::slug($request->name);
        $getSlugRes = Service::where('slug',$getSlug)->whereNotIn('id',array($request->service_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/service')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;
		$dataArray['category_id'] = $request->category_id;
		$dataArray['description'] = $request->description;
		$dataArray['short_description'] = $request->short_description;
		
		if(isset($request->image)){
			
			$courseInfo = Service::where('id',$request->service_id)->first();
			if(!empty($catInfo->image)){
				$getFilePath = public_path('uploads/service/').$courseInfo->image;
				if(file_exists($getFilePath)){
					unlink($getFilePath);
				}
			}
			
		    $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/service/'), $imageName); 
			$dataArray['image'] = $imageName;
		}
		
		$dataArray['status']      = $request->status;
		$dataArray['slug']        = $getSlug;
		
		Service::where('id',$request->service_id)->update($dataArray);

        return redirect('/admin/service')->with('success', 'You have successfully updated!');
    }
	
	public function delete_service($service_id){
		
		$courseInfo = Service::where('id',$service_id)->first();
		if(!empty($courseInfo->image)){
			$getFilePath = public_path('uploads/service/').$courseInfo->image;
			if(file_exists($getFilePath)){
				unlink($getFilePath);
			}
		}
		Service::where('id',$service_id)->delete();
		return redirect('/admin/service')->with('success', 'You have successfully deleted!');
	} 

	#SERVICES END


	#COURSE START

	public function all_course(){
		
		// $courseList = Course::select('course.*','category.name as category_name')->join('category','category.id','=','course.category_id')->get();
        return view('admin.course.list');
    }
	
	public function add_course(){ 
	    
		$category = Category::get();
        $board = SubCategory::get();
        $class = ClassModel::get();
        $subject= Subject::get();
        $topics=Topology::get();
        $usernames = Faculty_inform::get();
		return view('admin.course.add_new',compact('category','board','class','subject','topics','usernames')); 

     }


	public function add_course_action(Request $request){

		$datas = New All_course;   
  if(isset($request->thumbnail)){
        $thumbnails = time().'.'.$request->thumbnail->extension();  
        $request->thumbnail->move(public_path('uploads/admin/'), $thumbnails); 
        $datas['thumbnail']= $thumbnails;
        }

$datas['category_id']=$request->category_id;
$datas['board_id']=$request->board_id;
$datas['class_id']=$request->class_id;
$datas['subject_id']=$request->subject_id;
$datas['course_name']=$request->course_name;
$datas['course_code']=$request->course_code;
$datas['description']=$request->description;
$datas['date']=$request->date;
$datas['course_prize']=$request->course_prize;
$datas['dis_prize']=$request->dis_prize;
$datas['level']=$request->level;
$datas['lang']=$request->lang;
$datas['thumbnail']=$request->thumbnail;
$datas['v_url']=$request->v_url;

$check= $datas->save();

$unit_name=$request->unit_name;
$unit_code=$request->unit_code;
$teacher=$request->teacher;
$prize=$request->prize;

 $id = All_course::orderBy('id', 'desc')->first();

for($i=0; $i<count($unit_name); $i++){
	$datasave=[
	'course_id'=>$id->id,
     'unit_name' =>$unit_name[$i],
     'unit_code' =>$unit_code[$i],
     'teacher' =>$teacher[$i],
     'prize' =>$prize[$i]
	];

	DB::table('course_unit')->insert($datasave);
}


$Chapter_Name=$request->Chapter_Name;
$sub_topic=$request->sub_topic;
$question=$request->question;


 
 $id = All_course::orderBy('id', 'desc')->first();
 $ids = Course_unit::orderBy('id', 'desc')->first();


for($i=0; $i<count($Chapter_Name); $i++){

	
	$datasaves=[

	'course_id'=>$id->id,
	'unit_id'=>$ids->id,
    'Chapter_Name' =>$Chapter_Name[$i],
    'sub_topic' =>$sub_topic[$i],
    'question' =>$question[$i]
	];

	DB::table('course_topic')->insert($datasaves);
}


if($check)
{

	return redirect('admin/add-course')->with('success', 'You have successfully deleted!');
}
else
{
      return redirect('admin/add-course')->with('Failed', 'You have Not added!');
}


}



 public function edit_course($category_id){
		
		$category = Category::get();
		$courseInfo = Course::where('id',$category_id)->first();
        return view('admin.course.edit',compact('courseInfo','category'));
	}
	
	public function edit_course_action(Request $request){
		
		$request->validate([
            'name'           => ['required'],
			//'image'          => ['required','image|mimes:jpg,png,jpeg,gif,svg'],
            'price'          => ['required'],
            'category_id'    => ['required'],
            'description'    => ['required'],
        ]);
		
		$getSlug    = \Str::slug($request->name);
        $getSlugRes = Course::where('slug',$getSlug)->whereNotIn('id',array($request->course_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/course')->with('error', 'This name is all ready taken!!');
        }
		
		$dataArray = array();
		$dataArray['name']        = $request->name;
		$dataArray['category_id'] = $request->category_id;
		$dataArray['price']       = $request->price;
		$dataArray['description'] = $request->description;
		
		if(isset($request->image)){
			
			$courseInfo = Course::where('id',$request->category_id)->first();
			if(!empty($catInfo->image)){
				$getFilePath = public_path('uploads/course/').$courseInfo->image;
				if(file_exists($getFilePath)){
					unlink($getFilePath);
				}
			}
			
		    $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('uploads/course/'), $imageName); 
			$dataArray['image'] = $imageName;
		}
		
		$dataArray['status']      = $request->status;
		$dataArray['slug']        = $getSlug;
		
		Course::where('id',$request->course_id)->update($dataArray);

        return redirect('/admin/course')->with('success', 'You have successfully updated!');
    }
	
	public function delete_course($course_id){
		
		$courseInfo = Course::where('id',$course_id)->first();
		if(!empty($courseInfo->image)){
			$getFilePath = public_path('uploads/course/').$courseInfo->image;
			if(file_exists($getFilePath)){
				unlink($getFilePath);
			}
		}
		Course::where('id',$course_id)->delete();
		return redirect('/admin/course')->with('success', 'You have successfully deleted!');
	}
	
	public function course_info(){ 
	    
		// $category = Category::get();
        return view('admin.course.info');
    }
	public function course_pending(){ 
	    
		// $category = Category::get();
        return view('admin.course.pending');
    }

	#COURSE END 



	#LIBRARY START
	
	  function library(){
   
      $librarys= Library::all(); 
     return  view('admin.library.list',compact('librarys')); 
    }
	
	function add_library(){

		return view('admin.library.add_new');

    }

    function add_library_action(Request $request){
	
	$datas = New Library;
  if(isset($request->pdf)){
  	$extension = $request->file('pdf')->extension();
  	dd($extension);
        $imageName = time().'.'.$request->pdf->extension();  
        $request->pdf->move(public_path('uploads/admin/'), $imageName); 
        $datas['pdf']= $imageName;
    }
$datas['title']=$request->title;
$datas['payment']=$request->payment;
$datas['price']=$request->price;
$datas['date']=$request->date;

$check = $datas->save();

if($check){
    return redirect('admin/add_library')->with('success', 'You have successfully added!');

}
else{
    return redirect('admin/add_library')->with('Failed', 'You have Not added!');
}

  }



	 function edit_library($id){

		 $library = Library::where('id',$id)->first();
         return  view('admin.library.edit',compact('library'));
       }

public function edit_library_action(Request $request){
        

    $dataArray = array();
  
    $dataArray['title']  = $request->title;
    $dataArray['payment'] = $request->payment;
    $dataArray['price']  = $request->price;
    $dataArray['date']      = $request->date;

     if(isset($request->pdf)){
        
        $library = Library::where('id',$request->library_id)->first();
        if(!empty($library->pdf)){
            $getFilePath = public_path('uploads/admin/').$library->pdf;
            if(file_exists($getFilePath)){
                unlink($getFilePath);
            }
        }
        $imageName = time().'.'.$request->pdf->extension();  
        $request->pdf->move(public_path('uploads/admin/'), $imageName); 
        $dataArray['pdf'] = $imageName;
    }

    $dataArray['status'] = $request->status;

    
    Library::where('id', $request->library_id)->update($dataArray);
    return redirect()->back();
}

public function delete_library($id)
{
    Library::where('id',$id)->delete();
    return redirect('admin/library')->with('success', 'You have successfully deleted!');
}

	#LIBRARY END 
	
	 
	#DEPARTMENTS STARTS
	
	function department(){
		return view('admin.department.list');
	 }
	
	 function add_department(){
		return view('admin.department.add_new');
	 }
	 function edit_department(){
		return view('admin.department.edit');
	 }
	#DEPARTMENTS END 
	 

	#STAFF START
	 
	  public function staff()
      {
         $staffs= Staff::all();
         return  view('admin.staff.list',compact('staffs'));
      }
	
	 function add_staff(){
		return view('admin.staff.add_new');
	 }



      public function add_staff_action(Request $request)
{
	$request->validate([
          
         'password_confirmation' => 'same:password',         
         'account_confirmation' => 'same:account_no'


        ]);

$datas = New Staff;
 if(isset($request->img)){
        $imageName = time().'.'.$request->img->extension();  
        $request->img->move(public_path('uploads/admin/'), $imageName); 
        $datas['img']= $imageName;
    }

$datas['fname']=$request->fname;
$datas['lname']=$request->lname;
$datas['phone']=$request->phone;
$datas['dob']=$request->dob;
$datas['email']=$request->email;
$datas['gender']=$request->gender;
$datas['ref_code']=$request->ref_code;
$datas['ref_name']=$request->ref_name;
$datas['state']=$request->state;
$datas['city']=$request->city;
$datas['username']=$request->username;
$datas['password']=$request->password;
$datas['holder_name']=$request->holder_name;
$datas['ifsc']=$request->ifsc;
$datas['account_no']=$request->account_no;
$datas['upi']=$request->upi;

$check = $datas->save();

if($check){
    return redirect('admin/add_staff')->with('success', 'You have successfully added!');

}
else{
    return redirect('admin/add_staff')->with('Failed', 'You have Not added!');
}

  }



	 function edit_staff($id){

	 	$staff = Staff::where('id',$id)->first();
        
        return view('admin.staff.edit',compact('staff'));
	
	 }

public function edit_staff_action(Request $request){

    $dataArray = array();
  
    $dataArray['fname']  = $request->fname;
    $dataArray['lname'] = $request->lname;
    $dataArray['phone']  = $request->phone;
    $dataArray['dob'] = $request->dob;
    $dataArray['email']  = $request->email;
    $dataArray['gender']  = $request->gender;
    $dataArray['ref_code'] = $request->ref_code;
    $dataArray['ref_name'] = $request->ref_name;
    $dataArray['state'] = $request->state;
    $dataArray['city'] = $request->city;


     if(isset($request->img)){
        
        $staff = Staff::where('id',$request->staff_id)->first();
        if(!empty($staff->img)){
            $getFilePath = public_path('uploads/admin/').$staff->img;
            if(file_exists($getFilePath)){
                unlink($getFilePath);
            }
        }
        $imageName = time().'.'.$request->img->extension();  
        $request->img->move(public_path('uploads/admin/'), $imageName); 
        $dataArray['img'] = $imageName;
    }

    $dataArray['username'] = $request->username;
    $dataArray['password'] = $request->password;
    $dataArray['holder_name'] = $request->holder_name;
    $dataArray['ifsc'] = $request->ifsc;
    $dataArray['account_no'] = $request->account_no;
    $dataArray['upi'] = $request->upi;

    Staff::where('id',$request->staff_id)->update($dataArray);
    return redirect()->back();
    }

	 
	 function staff_profile(){
		return view('admin.staff.profile');
	 }

   #STAFF END 	 


   #QUESTIONS START

	public function all_question(){
		
		$questionList = Question::select('question.*','course.name as course_name','category.name as category_name')->join('course','course.id','=','question.course_id')->join('category','category.id','=','course.category_id')->get();
		//dd($questionList);
        return view('admin.question.question',compact('questionList'));
    }
	
	public function add_question(){ 
	    
		$course = Course::get();
        return view('admin.question.add_question',compact('course'));
    }
	
	public function add_question_action(Request $request){
		
		$request->validate([
            'title'          => ['required','unique:question'],
            'course_id'      => ['required'],
			//'pdf_file'       => ['required','image|mimes:pdf'],
			'pdf_file'       => ['required'],
            'description'    => ['required'],
        ]);
		
		$slug  = Str::slug($request->name);
        $count = Question::where('slug',$slug)->count();
        if($count>0){
            $slug=$slug.'-'.date('ymdis').'-'.rand(0,999);
        }
		
		if(isset($request->pdf_file)){
		    $fileName = time().'.'.$request->pdf_file->extension();  
            $request->pdf_file->move(public_path('uploads/question/'), $fileName); 
		}else{
			$fileName = '';
		}
		 
		$question = new Question;
		$question->title         = $request->title;
		$question->slug          = $slug;
		$question->pdf_file      = $fileName;
		$question->course_id     = $request->course_id;
		$question->description   = $request->description;
		$question->is_paid       = $request->is_paid;
		$question->save(); 
		
        return redirect('/admin/question')->with('success', 'You have successfully added!');
    }
	
	public function edit_question($question_id){
		
		$course = Course::get();
		$questionInfo = Question::where('id',$question_id)->first();
        return view('admin.question.edit_question',compact('questionInfo','course'));
	}
	
	public function edit_question_action(Request $request){
		
		$request->validate([
            'title'          => ['required'],
            'course_id'      => ['required'],
            'description'    => ['required'],
        ]);
		
		$getSlug    = \Str::slug($request->title);
        $getSlugRes = Question::where('slug',$getSlug)->whereNotIn('id',array($request->question_id))->count();
        
        if($getSlugRes>0){
            return redirect('/admin/question')->with('error', 'This name is all ready taken!!');
        }
		
		$question = Question::where('id',$request->question_id)->first();
		$question->title         = $request->title;
		$question->is_paid       = $request->is_paid;
		$question->slug          = $getSlug;
		
		if(isset($request->pdf_file)){
			$questionInfo = Question::where('id',$request->question_id)->first();
			if(!empty($questionInfo->pdf_file)){
				$getFilePath = public_path('uploads/question/').$questionInfo->pdf_file;
				if(file_exists($getFilePath)){
					unlink($getFilePath);
				}
			}
		    $fileName = time().'.'.$request->pdf_file->extension();  
            $request->pdf_file->move(public_path('uploads/question/'), $fileName);
			
			$question->pdf_file = $fileName;			
		}
		
		$question->course_id     = $request->course_id;
		$question->description   = $request->description;
		$question->save(); 
		
        return redirect('/admin/question')->with('success', 'You have successfully updated!');
    }
	
	public function delete_question($question_id){
		
		$questionInfo = Question::where('id',$question_id)->first();
		if(!empty($questionInfo->pdf_file)){
			$getFilePath = public_path('uploads/question/').$questionInfo->pdf_file;
			if(file_exists($getFilePath)){
				unlink($getFilePath);
			}
		}
		Question::where('id',$question_id)->delete();
		return redirect('/admin/question')->with('success', 'You have successfully deleted!');
	}
	public function review_question(){ 
	    
	 
        return view('admin.question.review');
    }
	public function report_question(){ 
	    
	 
        return view('admin.question.report' );
    }

 #QUESTIONS END


}
