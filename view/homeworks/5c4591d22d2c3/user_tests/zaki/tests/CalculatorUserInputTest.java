import java.util.Scanner;

public class CalculatorUserInputTest {
	
	public static void main(String[] args) {
		doTests();
	}

	public static void doTests() {
		Scanner sc = new Scanner(System.in);
		int a = sc.nextInt();
		int b = sc.nextInt();
		int c = sc.nextInt();
		
		Calculator c = new Calculator();
		if (c.multiply(a, b) == c) {
			System.out.println("OK");	
		} else {
			System.out.println(a + " * " + b + " != " + c);
		}
	}
}
