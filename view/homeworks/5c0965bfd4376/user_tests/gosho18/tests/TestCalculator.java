import static org.junit.Assert.assertEquals;

import org.junit.Before;
import org.junit.Test;

public class TestCalculator {
	
	private Calculator c;
	
	@Before
	public void initCalculator() {
		c = new Calculator();
	}
	
	@Test
	public void testSum() {
		int result = c.sum(2, 2);
		assertEquals(4, result);
	}
	
	@Test
	public void testSum1() {
		int result = c.sum(3, 2);
		assertEquals(5, result);
	}

	@Test
	public void testMinus() {
		int result=c.sum(3, -3);
		assertEquals(0, result);	
	}
}

