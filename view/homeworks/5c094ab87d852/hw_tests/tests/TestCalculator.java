import org.junit.jupiter.api.*;
import static org.junit.jupiter.api.Assertions.assertEquals;

public class TestCalculator {

	private Calculator c;

	@Before
	public void createCalculator() {
		c = new Calculator();
	}

	@Test
	public void testSum() {
		int result = c.sum(1, 2);
		assertEquals(3, result);
	}
}
