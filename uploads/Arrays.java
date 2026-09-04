import java.util.Scanner;
import java.util.Arrays;
class Arrays{
    public static void main(String[]args){
        Scanner sc=new Scanner(System.in);
        int[]arr=new int[5];
        System.out.println(arr[0]);

        arr[0]=101;
        arr[1]=102;
        arr[2]=103;
        arr[3]=104;
        arr[4]=105;
        System.out.println(arr[3]);

//        for(int i=0; i<arr.length; i++){t
//            System.out.println(arr[i]);
//        }
        for(int i=0; i<arr.length; i++){
            arr[i]=sc.nextInt();

        }

        System.out.println(Arrays.toString(arr));

//        for(int i=0; i<arr.length; i++){
//            System.out.print(arr[i]+" ");
//       }


    }
}