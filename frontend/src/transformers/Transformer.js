/**
 * @vuepress
 * ---
 * title: Transformers Base
 * headline: Transforming input/output data
 * ---
 */

/**
 * @author Virgilio Vázquez J. <virgilio.vazquez@jbge.com.mx>
 * @class Transformer
 * The base transformer.
 *
 * Transformers are used to transform the fetched data
 * to a more suitable format.
 * For instance, when the fetched data contains snake_cased values,
 * they will be camelCased.
 */
class Transformer {
  /**
   * Method used to transform a single fetched item
   * @param  {Object} item The item to transform
   * @return {Object}      The transformed item
   */
  static fetch (item) {
    return item
  }

  /**
   * Method used to transform a single sending item
   * @param  {Object} item The item to transform
   * @return {Object}      The transformed item
   */
  static send (item) {
    return item
  }

  /**
   * Method used to transform a fetched collection
   * @memberOf Transformer
   *
   * @param items The items to be transformed
   * @returns {Array} The transformed items
   */
  static fetchCollection (items) {
    return items.map(item => this.fetch(item))
  }

  /**
   * Method used to transform a collection to be send
   *
   * @param items The items to be transformed
   * @returns {Array} The transformed items
   */
  static sendCollection (items) {
    return items.map(item => this.send(item))
  }
}

export default Transformer
