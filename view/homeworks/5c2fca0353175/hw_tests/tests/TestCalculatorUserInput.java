
import java.util.Scanner;

public class TestCalculatorUserInput {
	
	public static void main(String[] args) throws Exception {
		Calculator c = new Calculator();
		doTests(c);		
	}
	
	public static void doTests(Calculator c) throws Exception {
		Scanner sc = new Scanner(System.in);
		int a = sc.nextInt();
		int b = sc.nextInt();
		int result = sc.nextInt();
		
		if (c.sum(a, b) != result) {
			throw new Exception(a + " + " + b + " is not equals to " + result);
		}
	}
}
