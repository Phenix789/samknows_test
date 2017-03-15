CREATE TABLE IF NOT EXISTS metrics_aggregate (
  id INT NOT NULL AUTO_INCREMENT,
  metric_name VARCHAR(20) NOT NULL,
  metric_min INT NOT NULL,
  metric_max INT NOT NULL,
  metric_avg INT NOT NULL,
  metric_med INT NOT NULL,
  metric_count INT NOT NULL,
  metric_time TIMESTAMP NOT NULL,
  PRIMARY KEY (id)
)
;
