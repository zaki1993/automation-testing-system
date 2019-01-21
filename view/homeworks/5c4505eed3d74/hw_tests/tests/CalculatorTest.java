import static org.junit.jupiter.api.Assertions.assertEquals;

import org.junit.jupiter.api.Test;

public class CalculatorTest {

	@Test
	public void testSum() {
		Calculator c = new Calculator();
		assertEquals(c.sum(1, 2), 3);
	}

	@Test
	public void testMultiply() {
		Calculator c = new Calculator();
		assertEquals(c.multiply(3, 2), 6);
	}

}