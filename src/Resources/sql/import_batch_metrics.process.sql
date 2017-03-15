INSERT
  INTO metrics_aggregate (metric_name, metric_min, metric_max, metric_avg, metric_med, metric_count, metric_time)
  SELECT
      metric_name,
      MIN(metric_value) AS metric_min,
      MAX(metric_value) AS metric_max,
      FLOOR(AVG(metric_value)) AS metric_avg,
      0 AS metric_med,
      COUNT(*) AS metric_count,
      metric_time
    FROM samknows.import_batch_metrics
    GROUP BY metric_name, metric_time
    ORDER BY metric_name, metric_time
;
