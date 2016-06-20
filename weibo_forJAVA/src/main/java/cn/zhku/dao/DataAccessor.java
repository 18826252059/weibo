package cn.zhku.dao;

import java.util.List;
import java.util.Map;

import javax.persistence.EntityManager;
import javax.persistence.EntityManagerFactory;
import javax.persistence.Persistence;
import javax.persistence.Query;
import javax.persistence.TypedQuery;
/**
 * 数据库访问工具类
 * @author Matthew
 *
 */
public class DataAccessor {
	
	private static EntityManager _manager;
	public static EntityManager getManager(){
		if(_manager == null){
			EntityManagerFactory factory = Persistence.createEntityManagerFactory("weibo");
			_manager = factory.createEntityManager();
		}
		return _manager;
	}
	
	public static void saveNew(Object entity){
		DataAccessor.getManager().getTransaction().begin();// 开启事务
		DataAccessor.getManager().persist(entity);
		DataAccessor.getManager().getTransaction().commit();// 提交事务
	}
	
	public static void update(Object entity){
		DataAccessor.getManager().getTransaction().begin();// 开启事务
		DataAccessor.getManager().merge(entity);
//		query.executeUpdate();
		DataAccessor.getManager().getTransaction().commit();// 提交事务
	}
	
	public static <T> List<?> findAll(Class<T> resultClass){
		String sql = String.format("select x from %s x", resultClass.getSimpleName());
		TypedQuery<?> q = DataAccessor.getManager().createQuery(sql, resultClass);
		return q.getResultList();
	}
	
	public static void delete(Class<?> entityClass,String id){
		Object ent = DataAccessor.getManager().find(entityClass, id);
		DataAccessor.getManager().remove(ent);
	}
	

}
