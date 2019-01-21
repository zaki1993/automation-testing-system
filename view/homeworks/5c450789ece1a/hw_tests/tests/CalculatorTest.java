import static org.junit.Assert.assertEquals;

import org.junit.Test;

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
