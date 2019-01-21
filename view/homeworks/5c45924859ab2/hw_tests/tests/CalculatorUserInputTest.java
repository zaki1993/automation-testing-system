import java.util.Scanner;

public class CalculatorUserInputTest {
	
	public static void main(String[] args) {
		doTests();
	}

	public static void doTests() {
		Scanner sc = new Scanner(System.in);
		int a = sc.nextInt();
		int b = sc.nextInt();
		int result = sc.nextInt();
		
		Calculator c = new Calculator();
		if (c.multiply(a, b) == result) {
			System.out.println("OK");	
		} else {
			System.out.println(a + " * " + b + " != " + c);
		}
	}
}
