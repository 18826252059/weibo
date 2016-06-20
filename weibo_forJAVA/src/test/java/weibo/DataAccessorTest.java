package weibo;

import java.sql.Date;
import java.sql.Timestamp;
import java.util.List;

import org.junit.Before;
import org.junit.Test;

import cn.zhku.dao.DataAccessor;
import cn.zhku.modal.Weibo_User;

public class DataAccessorTest {

	@Before
	public void setUp() throws Exception {
	}

//	@Test
//	public void testSaveNew() {
//		Date birth = new Date(System.currentTimeMillis());
//		Timestamp re = new Timestamp(System.currentTimeMillis());
//		Weibo_User u = new Weibo_User("test", "123", "HMZ", "null", "null", "男", birth, "null", "null", re, "18826252059");
//		DataAccessor.saveNew(u);
//		System.out.println("done");
//	}
//
//	@Test
//	public void testUpdate() {
//		Date birth = new Date(System.currentTimeMillis());
//		Timestamp re = new Timestamp(System.currentTimeMillis());
//		Weibo_User u = new Weibo_User("1084529403", "123456", "HMZ", "null", "null", "男", birth, "null", "null", re, "18826252059");
//		DataAccessor.update(u);
//		System.out.println("2done");
//	}
//
//	@Test
//	public void testFindAll() {
//		List<?> ls = DataAccessor.findAll(Weibo_User.class);
//		System.out.println(ls.size());
//	}

	@Test
	public void testDelete() {
		DataAccessor.delete(Weibo_User.class, "test");
		System.out.println("4done");
	}

}
